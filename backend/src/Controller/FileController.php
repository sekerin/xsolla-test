<?php
/** @noinspection PhpUnusedAliasInspection */

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 */
class FileController extends AbstractController
{
    /**
     * Get list of files metadata
     *
     * @Route("/", name="files_list", methods="GET")
     */
    public function index(): Response
    {
        return new Response('index');
    }

    /**
     * Start file download
     *
     * @Route(
     *     "/{file_name}",
     *     name="item_get",
     *     methods="GET",
     *     requirements={"file_name": "[A-Za-z0-9 _ \.\-]+"}
     *     )
     *
     * @param Request $request
     * @return Response
     */
    public function download(Request $request): Response
    {
        $fileName = $request->get('file_name') ?? '';

        return new Response('download' . $fileName);
    }

    /**
     * Create or update file
     *
     * @Route("/", name="file_create", methods="POST")
     * @Route("/{file_name}",
     *     name="file_update",
     *     methods="POST",
     *     requirements={"file_name": "[A-Za-z0-9 _ \.\-]+"}
     *     )
     */
    public function save(): Response
    {
        return new Response('save');
    }

    /**
     * Delete file
     *
     * @Route(
     *     "/{file_name}",
     *     name="file_delete",
     *     methods="DELETE",
     *     requirements={"file_name": "[A-Za-z0-9 _ \.\-]+"}
     *     )
     *
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $fileName = $request->get('file_name') ?? '';

        return new Response('delete' . $fileName);
    }
}
