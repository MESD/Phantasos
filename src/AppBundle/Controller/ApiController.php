<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

use Component\Preparer\UploadTicketRequest;
use Component\Exceptions\Api\MissingParameterException;
use Component\Exceptions\Files\UnsupportedFileTypeException;

/**
 * Controller for API calls
 */
class ApiController extends Controller
{
    /**
     * @Route("/api/requestUploadTicket", name="requestUpload")
     * @Method({"POST"})
     *
     * This action will request a new upload ticket
     */
    public function requestUploadTicketAction(Request $request)
    {
        // Process the input and create a new upload ticket request
        $uploadRequest = new UploadTicketRequest();

        // Get the parameter values or their defaults
        if ($request->request->has('tags')) {
            $uploadRequest->setTags(
                explode(',', $request->request->get('tags'))
            );
        } else {
            $uploadRequest->setTags(array());
        }

        if ($request->request->has('security')) {
            $uploadRequest->setSecurityTags(
                explode(',', $request->request->get('security'))
            );
        } else {
            $uploadRequest->setSecurityTags(array());
        }

        if ($request->request->has('hide_to_others')) {
            $uploadRequest->setHideToOthers(
                filter_var(
                    $request->request->get('hide_to_others'),
                    FILTER_VALIDATE_BOOLEAN
                )
            );
        } else {
            $uploadRequest->setHideToOthers(true);
        }

        if ($request->request->has('callback_route')) {
            $uploadRequest->setCallback(
                $request->request->get('callback_route')
            );
        }

        // Set the application name
        $uploadRequest->setApplicationName(
            $this->get('security.context')->getToken()->getUsername()
        );

        // Pass the upload ticket request to the preparer and get an upload ticket back
        $ticket = $this->get('phantasos.preparer')->requestUploadTicket($uploadRequest);

        // Generate the route
        $ticket->setUploadRoute(
            $this->generateUrl(
                'uploadMedia',
                array('ticketId' => $ticket->getTicketId()),
                true
            )
        );

        // Return the JSON response
        return new JsonResponse($ticket->toArray());
    }

    /**
     * @Route("/api/uploadMedia/{ticketId}", name="uploadMedia")
     * @Method({"POST", "OPTIONS"})
     *
     * Action to upload media to the file store
     */
    public function uploadMediaAction(Request $request, $ticketId)
    {
        // NOTE!!! The CORS headers here really need to be cleaned up!!!!
        ////
        /////
        //////


        // Check for the options request
        if ('OPTIONS' === $request->getMethod()) {
            $response = new Response('Ok', 200);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
            return $response;
        }

        // Check that the ticket id exists
        $media = $this->get('phantasos.storage')->getMediaById($ticketId);
        if (null === $media) {
            $response = new Response('Not a valid ticket', 400);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
            return $response;
        }

        // Check that an upload has not already happened
        if (true === $media->getUploaded()) {
            $response = new Response('Upload ticket already used', 400);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
            return $response;
        }

        // Check that the ticket has not expired
        $current = new \DateTime();
        if ($current > $media->getUploadExpiration()) {
            $response = new Response('Not a valid ticket', 400);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
            return $response;
        }

        // Check that the file was given
        if (!$request->files->has('file_data')) {
            $response = new Response('File was not given', 400);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
            return $response;
        }

        // Put the file into storage
        try {
            $this->get('phantasos.preparer')->handleUpload(
                $request->files->get('file_data'),
                $media->getId()
            );
        } catch (UnsupportedFileTypeException $e) {
            $response = new Response($e->getMessage(), 400);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
            return $response;
        } catch (\Exception $e) {
            throw $e;
        }

        // Respond
        $response = new Response('Upload accepted', 200);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-PINGOTHER, X-File-Name, Cache-Control, Origin');
        return $response;
    }

    /**
     * @Route("/api/mediaInfo", name="getMediaInfo")
     * @Method({"GET"})
     *
     * Get info on the requested media
     */
    public function getMediaInfoAction(Request $request)
    {
        // Get the parameters from the request
        if (!$request->query->has('media_id')) {
            return new Response('Field "media_id" is required', 400);
        }

        // Get the media info
        try {
            $mediaInfo = $this->get('phantasos.retrieval')->getMediaInfo(
                $request->query->get('media_id'),
                $this->get('security.context')->getToken()->getUsername()
            );
        } catch (\Exception $e) {
            return new Response('Media was not found', 404);
        }

        // Check that media info exists
        if (null === $mediaInfo) {
            return new Response('Requested media does not exist', 404);
        }

        // Return the info
        return new JsonResponse($mediaInfo->toArray());
    }

    /**
     * @Route("/api/requestMedia", name="serveMedia")
     * @Method({"GET"})
     *
     * Return raw media
     */
    public function serveMediaAction(Request $request)
    {
        // Get the media file id
        if (!$request->query->has('media_file_id')) {
            return new Response('Field "media_file_id" is required', 400);
        }

        // Get the file path
        $filePath = $this->get('phantasos.retrieval')->getFilePath(
            $request->query->get('media_file_id'),
            $this->get('security.context')->getToken()->getUsername()
        );

        // Create the new response
        $response = new BinaryFileResponse($filePath->getPath());
        $response->headers->set('Content-Type', $filePath->getContentType());

        // Return
        return $response;
    }
}
