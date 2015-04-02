<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $uploadRequest->setTags(explode(',', $request->request->get('tags')));
        } else {
            $uploadRequest->setTags(array());
        }

        if ($request->request->has('security')) {
            $uploadRequest->setSecurityTags(explode(',', $request->request->get('security')));
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
     * @Method({"POST"})
     *
     * Action to upload media to the file store
     */
    public function uploadMediaAction(Request $request, $ticketId)
    {
        // Check that the ticket id exists
        $media = $this->get('phantasos.storage')->getMediaById($ticketId);
        if (null === $media) {
            return new Response('Not a valid ticket', 400);
        }

        // Check that an upload has not already happened
        if (true === $media->getUploaded()) {
            return new Response('Upload ticket already used', 400);
        }

        // Check that the ticket has not expired
        $current = new \DateTime();
        if ($current > $media->getUploadExpiration()) {
            return new Response('Not a valid ticket', 400);
        }

        // Check that the file was given
        if (!$request->files->has('file_data')) {
            return new Response('File was not given', 400);
        }

        // Put the file into storage
        try {
            $this->get('phantasos.preparer')->handleUpload(
                $request->files->get('file_data'),
                $media->getId()
            );
        } catch (UnsupportedFileTypeException $e) {
            return new Response($e->getMessage(), 400);
        } catch (\Exception $e) {
            throw $e;
        }

        // Respond
        return new Response('Upload accepted', 200);
    }
}
