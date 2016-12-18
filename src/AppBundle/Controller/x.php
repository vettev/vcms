<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CategoryType;
use AppBundle\Form\SettingsType;
use AppBundle\Form\UserType;
use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Entity\Settings;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends Controller
{
    /**
     * @Route("admin/panel/", name="admin_panel")
     */
    public function panelAction(Request $request)
    {
    	return $this->render('admin/panel.html.twig', []);
    }

    /**
     * @Route("admin/users/", name="admin_users")
     */
    public function usersAction(Request $request)
    {
    	$users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

    	return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("admin/categories/", name="admin_categories")
     */
    public function categoriesAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $category = new Category();

        $formAdd = $this->createForm(CategoryType::class, $category);
        $formAdd->handleRequest($request);

        if($formAdd->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('notice', 'Category added');
        }

        return $this->render('admin/categories.html.twig', ['categories' => $categories, 'formAdd' => $formAdd->createView()]);
    }

    /**
     * @Route("admin/settings", name="admin_settings")
     */
    public function settingsAction(Request $request)
    {
        $settings = $this->getDoctrine()->getRepository('AppBundle:Settings')->findOneBy([]);

        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();
        }

        return $this->render('admin/settings.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/creator", name="admin_creator")
     */
    public function creatorAction(Request $request)
    {
        $configFile = $this->get('kernel')->getRootDir() . '/config/parameters.yml';

        $parameters = Yaml::parse(file_get_contents($configFile));
        if(isset($parameters['parameters']['settings_flag']) && $parameters['parameters']['settings_flag'])
            return $this->redirectToRoute('homepage');

        $formAdmin = $this->createForm(UserType::class);
        $data = [];

        if($request->request->get('dbhost')) {
            $data['db_host'] = $request->request->get('dbhost');
            $data['db_name'] = $request->request->get('dbname');
            $data['db_user'] = $request->request->get('dbuser');
            $data['db_pass'] = $request->request->get('dbpass');
            $data['username'] = $request->request->get('user')['username'];
            $data['email'] = $request->request->get('user')['email'];
            $data['password'] = $request->request->get('user')['password']['first'];
            $data['password2'] = $request->request->get('user')['password']['second'];
            $data['title'] = $request->request->get('title');
            $data['desc'] = $request->request->get('description');
            $isOk = true;
            $alert = "";
            $emailCheck = "/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/";

            if(!$data['db_host'] || !$data['db_name'] || !$data['db_user'] || !$data['username'] || !$data['password'] || !$data['password2'] || !$data['title'] || !$data['desc']) {
                $isOk = false;
                $alert .= "You need to fill all required fields.<br/>";
            }
            if($data['password']!==$data['password2']) {
                $isOk = false;
                $alert .= "Passwords are not match<br/>";
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $isOk = false;
                $alert .= "Email seems to be invalid<br/>";
            }
            if(strlen($data['username']) > 32 || strlen($data['email']) > 64) {
                $isOk = false;
                $alert .= "Username can be up to 32 characters and email can be up to 64.<br/>";
            }
            if(!preg_match("/^[a-zA-Z0-9_.]+$/i", $data['username'])) {
                $isOk = false;
                $alert .= "Username is invalid, you can use letters and 2 special chars _.";
            }

            if(!$isOk) {
                $this->addFlash('error', $alert);
                return $this->redirectToRoute('admin_creator');
            }

            $parameters['parameters']['database_host'] = $data['db_host'];
            $parameters['parameters']['database_name'] = $data['db_name'];
            $parameters['parameters']['database_user'] = $data['db_user'];
            $parameters['parameters']['database_password'] = $data['db_pass'];

            $dumper = new Dumper(); 
            $dumper->setIndentation(4);
            $yaml = $dumper->dump($parameters, 2);
            $importFile = $this->get('kernel')->getRootDir() . '/../web/database.sql';
            if(file_put_contents($configFile, $yaml)) {
                $command='mysql --host=' .$data['db_host'] .' --user=' .$data['db_user'] .' --password=' .$data['db_pass'] .' ' .$data['db_name'] .' < ' .$importFile;
                $output = [];
                exec($command, $output, $worked);
                if(!$worked) {
                    $session = new Session();
                    if(!$request->hasPreviousSession())
                        $session->start();
                    $session->set('data', $data);
                    return $this->redirectToRoute('creator_persist');
                }    
            }
        }

        return $this->render('admin/creator.html.twig', ['formAdmin' => $formAdmin->createView()]);
    }
    /**
     * @Route("/creator/persist", name="creator_persist")
     */
    public function creatorPersistAction(Request $request)
    {
        $configFile = $this->get('kernel')->getRootDir() . '/config/parameters.yml';

        $parameters = Yaml::parse(file_get_contents($configFile));
        if(isset($parameters['parameters']['settings_flag']) && $parameters['parameters']['settings_flag'])
            return $this->redirectToRoute('homepage');

        $session = new Session();
        $data = $session->get('data');
        if(!$data)
            return $this->redirectToRoute('admin_creator');

        $admin = new User();
        $settings = new Settings();

        $role = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneByName('Admin');
        $password = $this->get('security.password_encoder')
                        ->encodePassword($admin, $data['password']);
        $admin->setPassword($password);
        $admin->setUsername($data['username']);
        $admin->setEmail($data['email']);
        $admin->setRole($role);
        $admin->setCreatedAt(new \Datetime());

        $settings->setTitle($data['title']);
        $settings->setDescription($data['desc']);
        $settings->setCustomMenu(false);
        $settings->setFooterContent('');

        $em = $this->getDoctrine()->getManager();
        $em->persist($admin);
        $em->persist($settings);
        $em->flush();

        $parameters['parameters']['settings_flag'] = true;
        $dumper = new Dumper(); 
        $dumper->setIndentation(4);
        $yaml = $dumper->dump($parameters, 2);
        if(file_put_contents($configFile, $yaml))
            $this->addFlash('notice', 'Creation successfull');

        return $this->redirectToRoute('admin_panel');

    }

    /**
     * @Route("/page/{id}/sethome", name="admin_set_homepage")
     */
    public function setAsHomepage($id, Request, $request)
    {

    }

}