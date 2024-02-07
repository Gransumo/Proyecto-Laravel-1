<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClientResource;


/**
* @OA\Info(
*             title="Clientes",
*             version="1.0",
*             description="Clientes"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
*/
class ClientController extends Controller
{
    /**
     * Listado de Clientes
     * @OA\Get (
     *     path="/api/client",
     *     tags={"List"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Gabriel Castro"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="pedrosanchez@examplee.com"
     *                     ),
     *                     @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         example="666666666"
     *                     ),
     *                     @OA\Property(
     *                         property="address",
     *                         type="string",
     *                         example="Calle Alcalá, 512. Madrid"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error: Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Server Error"),
     *          )
     *      )
     * )
     */
	public function index(): JsonResource
	{
		try {
            //return response()->json(Client::all(), 200);
			return ClientResource::collection(Client::all());
		} /* catch (QueryException $err) {
			return response()->json(['error' => 'Server error: ' . $err->getMessage()], 500);
		}  */catch (\Exception $err) {
			return response()->json(['error' => $err->getMessage()], 500);
		}
	}

    /**
     * Crear un nuevo registro de cliente
     * @OA\Post (
     *     path="/api/client",
     *     tags={"Store"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string"
     *                      )
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="email", type="string", example="pedrosanchez@swagger.com"),
     *              @OA\Property(property="phone", type="string", example="777 777 777"),
     *              @OA\Property(property="address", type="string", example="Madrid"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Error: Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation error: {Error Message}"),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(type="string", example="error1")
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(type="string", example="error2")
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="array",
     *                      @OA\Items(type="string", example="error3")
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="array",
     *                      @OA\Items(type="string", example="error4")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error: Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Server Error"),
     *          )
     *      )
     * )
     */
	public function store(ClientRequest $request): JsonResponse
	{
		try {
			$client = Client::create($request->validated());
			return response()->json($client, 201);
		} /* catch (ValidationException $err) {
			return response()->json(['error' => 'Validation error: ' . $err->getMessage()], 422);
		} catch (QueryException $err) {
			return response()->json(['error' => 'Server error: ' . $err->getMessage()], 500);
		}  */catch (\Exception $err) {
			return response()->json(['error' => 'Unexpected error: ' . $err->getMessage()], 500);
		}
	}

    /**
     * Mostrar la información de un cliente
     * @OA\Get (
     *     path="/api/client/{id}",
     *     tags={"Show"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="email", type="string", example="pedrosanchez@examplee.com"),
     *              @OA\Property(property="phone", type="string", example="666666666"),
     *              @OA\Property(property="address", type="string", example="Calle Alcalá, 512. Madrid"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error: Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Server Error"),
     *          )
     *      )
     * )
     */
	public function show(Client $client): JsonResponse
	{
		try {
			return response()->json($client, 200);
		} /* catch (ModelNotFoundException $err) {
			$err->setModel(Client::class);
			return response()->json(['error' => $err->getMessage()], 404);
		}  */catch (\Exception $err) {
            return response()->json(['error' => 'Unexpected error: ' . $err->getMessage()], 500);
		}
	}

    /**
     * Actualizar la información de un Cliente
     * @OA\Put (
     *     path="/api/clientes/{id}",
     *     tags={"Update"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string"
     *                      )
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="UPDATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Aderson Felix"),
     *              @OA\Property(property="email", type="string", example="pedrosanchez@swagger.com"),
     *              @OA\Property(property="phone", type="string", example="777 777 777"),
     *              @OA\Property(property="address", type="string", example="Madrid"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error: Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation error: {Error Message}"),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(type="string", example="error1")
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(type="string", example="error2")
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="array",
     *                      @OA\Items(type="string", example="error3")
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="array",
     *                      @OA\Items(type="string", example="error4")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error: Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Server Error"),
     *          )
     *      )
     * )
     */
	public function update(ClientRequest $request, Client $client): JsonResponse
	{
		try {
			$client->update($request->validated());
			return response()->json($client, 201);
		} /* catch (ValidationException $e) {
			return response()->json(['error' => 'Validation error: ' . $e->getMessage()], 422);
		} catch (ModelNotFoundException $err) {
			$err->setModel(Client::class);
			return response()->json(['error' => $err->getMessage()], 404);
		}  */catch (Exception $err) {
			return response()->json(['error' => 'Unexpected error: ' . $err->getMessage()], 500);
		}
	}

    /**
     * Mostrar la información de un cliente
     * @OA\Delete (
     *     path="/api/client/{id}",
     *     tags={"Delete"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Deleted"
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Error: Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error: Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Server Error"),
     *          )
     *      )
     * )
     */
	public function destroy(Client $client): JsonResponse
	{
		try {
			$client->delete();
			return response()->json([], 204);
		} /* catch (ModelNotFoundException $err) {
            $err->setModel(Client::class);
			return response()->json(['error' => $err->getMessage()], 404);
		} catch (QueryException $err) {
            return response()->json(['error' => 'DataBase error: ' . $err->getMessage()], 500);
        }  */catch (\Exception $err) {
            return response()->json(['error' => 'Unexpected error: ' . $err->getMessage()], 500);
        }
	}
}
