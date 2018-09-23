<?php
/** @noinspection PhpUnusedAliasInspection */

declare(strict_types=1);

namespace App\Controller;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileRepositoryInterface;

use Symfony\Component\Routing\Route as Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/files")
 */
class FileController extends AbstractController
{
    /**
     * Get list of files metadata
     *
     * @Route("/", name="files_list", methods="GET")
     *
     * @param FileRepositoryInterface $repository
     * @return Response
     */
    public function index(FileRepositoryInterface $repository): Response
    {
        $files = new \LimitIterator($repository->getFilesList(), 0, 10000);

        return $this->json([
            'items' => $files
        ]);
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
    public function download(Request $request, FileRepositoryInterface $repository): Response
    {
        $fileName = $request->get('file_name') ?? '';

        $repository->download($fileName);

        throw new \RuntimeException();
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
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FileEntityInterface $fileEntity
     * @param FileRepositoryInterface $repository
     *
     * @return Response
     */
    public function save(
        Request $request,
        ValidatorInterface $validator,
        FileEntityInterface $fileEntity,
        FileRepositoryInterface $repository
    ): Response {
        if ($request->files->get('file') instanceof File) {
            $fileEntity->setFile($request->files->get('file'));
        }

        $fileName = $request->get('file_name') ?? null;

        $errors = $validator->validate($fileEntity);

        if ($errors->count() > 0) {
            throw new BadRequestHttpException($errors->get(0)->getMessage(), null, 400);
        }

        /** @var string $fileName */
        $file = is_null($fileName)
            ? $repository->create($fileEntity)
            : $repository->replace($fileName, $fileEntity);

        return $this->json([
            'item' => $file->getMetadata()
        ]);
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
     * @param FileRepositoryInterface $repository
     * @return Response
     */
    public function delete(Request $request, FileRepositoryInterface $repository): Response
    {
        $fileName = $request->get('file_name') ?? '';

        if ($repository->delete($fileName)) {
            return $this->json(['item' => ['name' => $fileName]]);
        }

        throw new \RuntimeException();
    }

    /**
     * Response for options request
     *
     * @Route("/", name="files_options", methods="OPTIONS")
     * @Route("/{filename}", name="files_options_file", methods="OPTIONS", requirements={"file_name": "[A-Za-z0-9 _ \.\-]+"})
     *
     * @param Request $request
     * @return Response
     */
    public function options(Request $request): Response
    {
        $response = new Response();

        $response->setStatusCode(204);

        if ($request->get('filename')) {
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        } else {
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST');
        }


        return $response;
    }
}
