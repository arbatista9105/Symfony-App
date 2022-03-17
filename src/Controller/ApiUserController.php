<?php
	/**
	 * Created by PhpStorm.
	 * User: Ale
	 */

    namespace App\Controller;


    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    /**
    * Class ApiUserController
    * @package App\Controller
    * @Route("/api/res", name="post_api")
    */
    class ApiUserController extends AbstractController
    {

    /**
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @Route("/user/all", name="api_user_all", methods={"GET"})
     */
    public function getUserAll(UserRepository $userRepository){

        $users = $userRepository->findAll();
        $userArray = [];
        foreach ($users as $user){
            $userArray[] = [
                'Nombre'=>$user->getName(),
                'Apellidos' => $user->getLastname(),
                'Cêdula'=>$user->getDni(),
                'Correo'=>$user->getEmail(),
            ];
        };
        $data = [
            'code' => 200,
            'message' => "Usuarios listados con exito",
            'data' => $userArray
        ];
        return $this->response($data);
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response($data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

}
