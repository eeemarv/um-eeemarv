<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Category;
use Eeemarv\EeemarvBundle\Form\CategoryType;


//@
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class CategoryController extends Controller
{

	private $em;
	private $repo;

    /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:Category');
    }	

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories")
     * @Method({"GET"})
     */
    public function indexAction()
    {
		$categories = $this->repo->findBy(array(), array('left' => 'asc'));

		if (!sizeof($categories)){
			$categories = $this->createRoot();
		}

        return array('categories' => $categories);
    }

    
    /**
     * Secure(roles="ROLE_ADMIN")
     * @Route("/categories")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $entity  = new Category();
        $form = $this->createForm('eeemarv_category_type', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($entity);                                 
            $this->em->flush();
			$this->recoverTree();            
	
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
        }

        return $this->render('EeemarvBundle:Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories/{id}/new")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function newAction(Category $parentCategory)
    {
        $category = new Category();
        $category->setParent($parentCategory);
        $form   = $this->createForm('eeemarv_category_type', $category);
        return array(
            'entity' => $category,
            'form'   => $form->createView(),
			);
    }

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories/{id}", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @ParamConverter
     */
    public function showAction(Category $category)
    {
		//list($em, $repo, $entity) = $this->find($id);

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $category,
            //'delete_form' => $deleteForm->createView(),        
            );
    }

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories/{id}/edit", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @ParamConverter
     */
    public function editAction(Request $request, Category $category)
    {
		if (!$category->getLevel()){
			$request->getSession()->getFlashBag()->add('error', 'flash.error.edit.root');
			return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
		}	
		
        $form = $this->createForm('eeemarv_category_type', $category);
		
        return array(
            'entity'      => $category,
            'form'   => $form->createView(),
			);
    }

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories/{id}", requirements={"id"="\d+"})
     * @Method({"PUT"})
     * @ParamConverter
     */
    public function updateAction(Request $request, Category $category)
    {
        $editForm = $this->createForm('eeemarv_category_type', $category);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($category);
            $this->em->flush();
            $this->recoverTree(); 
			$request->getSession()->getFlashBag()->add('success', 'flash.success.edit.category');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
        }

        return $this->render('EeemarvBundle:Category:edit.html.twig', array(
            'entity'      => $category,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories/{id}", requirements={"id"="\d+"})
     * @Method({"DELETE"})
     * @ParamConverter
     */
    public function deleteAction(Request $request, $category)
    {
        $form = $this->createDeleteForm($category->getId());
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->remove($entity);
            $this->em->flush();
			$this->recoverTree();
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
    }

    /**
     * @param mixed $id 
     * @return Symfony\Component\Form\Form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    

	/** 
	 * Secure(roles="ROLE_ADMIN")
	 * @ParamConverter 
	 */
	public function sortChildrenAction(Category $category)
	{
		$this->repo->reorder($category, 'name', 'asc', false);
		$this->em->clear();		
		$this->recoverTree();	
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
	}	

	/** 
	 * Secure(roles="ROLE_ADMIN")
	 * @ParamConverter
	 */
	public function upAction(Category $category)
	{
		$this->repo->moveUp($category);
		$this->recoverTree();
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
	}	
	
	/** 
	 * Secure(roles="ROLE_ADMIN")
	 * @ParamConverter 
	 */
	public function downAction(Category $category)
	{
		$this->repo->moveDown($category);
		$this->recoverTree();
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
	}	
	
	/** 
	 * Secure(roles="ROLE_ADMIN")
	 * @ParamConverter
	 */
	public function rightAction(Category $category)
	{	 
		
		$prevSiblings = $this->repo->getPrevSiblings($category);  //
		
		if (!sizeof($prevSiblings)){
			// exception
		}	 
    
        $this->repo->persistAsLastChildOf($category, end($prevSiblings));
        $this->em->flush();
        $this->recoverTree();
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
	}	
	
	/** 
	 * Secure(roles="ROLE_ADMIN")
	 * @ParamConverter
	 */
	public function leftAction(Category $category)
	{
        if ($category->getLevel() < 2){
			// todo exception
			exit;
		}
		$this->repo->persistAsNextSiblingOf($category, $category->getParent());
        $this->em->flush(); 
		$this->recoverTree();	

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));		
	}

    /**
     * Deletes a Category entity.
     * Secure(roles="ROLE_ADMIN")
     * @ParamConverter 
     */
    public function removeAction(Request $request, Category $category)
    {
		// todo : exceptions

		$this->em->remove($category);
		$this->em->flush();
		$this->recoverTree();

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));
    }

	/** 
	 * Secure(roles="ROLE_ADMIN")
     * @Route("/categories/{id}/button", requirements={"id"="\d+"})
     * @Method({"POST"})
     * @ParamConverter
	 */
	public function buttonAction(Request $request, $id)
	{
		if ($request->get('add', null)) {
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_new', array('id' => $id)));
		}
		if ($request->get('sort', null)) {
            return $this->forward('EeemarvBundle:Category:sortChildren', array('id' => $id));
		}		
		if ($request->get('up', null)) {
            return $this->forward('EeemarvBundle:Category:up', array('id' => $id));
		}
		if ($request->get('down', null)) {
            return $this->forward('EeemarvBundle:Category:down', array('id' => $id));
		}		
		if ($request->get('right', null)) {
            return $this->forward('EeemarvBundle:Category:right', array('id' => $id));
		}
		if ($request->get('left', null)) {
            return $this->forward('EeemarvBundle:Category:left', array('id' => $id));
		}
		if ($request->get('edit', null)) {
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_edit', array('id' => $id)));
		}
		if ($request->get('delete', null)) {
            return $this->forward('EeemarvBundle:Category:remove', array('id' => $id));
		}

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));		
	}



    private function recoverTree()
    {
		$this->repo->verify();
		$this->repo->recover();
		$this->em->clear();
		$this->em->flush(); 	
	}
	
	private function createRoot()
	{
		$category = new Category();
		$category->setName('[root]');  // translate default lang?
		$this->em->persist($category);
		$this->em->flush();
		return $this->repo->findBy(array(), array('left' => 'asc'));
	}	
	
		
}
