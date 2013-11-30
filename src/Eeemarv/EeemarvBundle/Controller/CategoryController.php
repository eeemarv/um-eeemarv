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
		$cats = $this->repo->findBy(array(), array('left' => 'asc');

		$categories = $level_ref = array();

		foreach ($cats as $key => $cat)
		{
			$level = $cat['level'];
			if ($level){
				$level_ref[$level - 1]['children'][$key] = $cat;
				$level_ref[$level] = &$level_ref[$level - 1]['children'][$key];
				$level_ref[$level]['children'] = array();
				
			} else {
				$categories[$key] = $cat;
				$categories[$key]['children'] = array();
				$level_ref[$level] = &$categories[$key];
			}
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);                                 
            $em->flush();
            
			$this->recoverTree($em);            
	
            return $this->redirect($this->generateUrl('eeemarv_categories_show', array('id' => $entity->getId())));
        }

        return $this->render('EeemarvBundle:Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**.
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function newAction($parent_id)
    {
        $entity = new Category();
        
		if ($parent_id){
			list($em, $repo, $parentCategory) = $this->find($parent_id); 
			$entity->setParent($parentCategory);
		}	
        
        $form   = $this->createForm('eeemarv_category_type', $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
			);
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/categories/{slug}")
     * @Method({"GET"})
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
     * Displays a form to edit an existing Category entity.
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function editAction($id)
    {
		list($em, $repo, $entity) = $this->find($id);

        $editForm = $this->createForm('eeemarv_category_type', $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
			);
    }

    /**
     * Edits an existing Category entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateAction(Request $request, $id)
    {
		list($em, $repo, $entity) = $this->find($id);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm('eeemarv_category_type', $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            $this->recoverTree($em); 

            return $this->redirect($this->generateUrl('eeemarv_categories'));
        }

        return $this->render('EeemarvBundle:Category:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
			list($em, $repo, $entity) = $this->find($id);

            $em->remove($entity);
            $em->flush();

			$this->recoverTree($em);
        }

        return $this->redirect($this->generateUrl('eeemarv_categories'));
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
    
    private function recoverTree($em)
    {
		$repo = $em->getRepository('EeemarvBundle:Category');
		$repo->verify();
		$repo->recover();
		$em->clear();
		$em->flush(); 	
	}

	/** 
	 * Secure(roles="ROLE_ADMIN")
	 */
	public function AddChildAction($id)
	{
		list($em, $repo, $entity) = $this->find($id);

		// todo

		$this->recoverTree($em);
        return $this->redirect($this->generateUrl('eeemarv_categories'));
	}	

	/** 
	 * Secure(roles="ROLE_ADMIN")
	 */
	public function sortChildrenAction($id)
	{
		list($em, $repo, $entity) = $this->find($id);
		
		$repo->reorder($entity, 'name', 'asc', false);
		$em->clear();		
		$this->recoverTree($em);	
        return $this->redirect($this->generateUrl('eeemarv_categories'));
	}	

	/** 
	 * Secure(roles="ROLE_ADMIN")
	 */
	public function upAction($id)
	{
		list($em, $repo, $entity) = $this->find($id);
		
		$repo->moveUp($entity);
		$this->recoverTree($em);
        return $this->redirect($this->generateUrl('eeemarv_categories'));
	}	
	
	/** 
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function downAction($id)
	{
		list($em, $repo, $entity) = $this->find($id);
		
		$repo->moveDown($entity);
		$this->recoverTree($em);
        return $this->redirect($this->generateUrl('eeemarv_categories'));
	}	
	
	/** 
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function rightAction($id)
	{
		list($em, $repo, $entity) = $this->find($id);
		 
		$prevSiblings = $repo->getPrevSiblings($entity);  //
		
		if (!sizeof($prevSiblings)){
			// exception
		}	 
		    
        $repo->persistAsLastChildOf($entity, end($prevSiblings));
        $em->flush();
        $this->recoverTree($em);
        return $this->redirect($this->generateUrl('eeemarv_categories'));
	}	
	
	/** 
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function leftAction($id)
	{
		list($em, $repo, $entity) = $this->find($id);

        if ($entity->getLevel() < 2){
			// todo exception
			exit;
		}
		$repo->persistAsNextSiblingOf($entity, $entity->getParent());
        $em->flush(); 
		$this->recoverTree($em);	

        return $this->redirect($this->generateUrl('eeemarv_categories'));		
	}

    /**
     * Deletes a Category entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function removeAction(Request $request, $id)
    {
		list($em, $repo, $entity) = $this->findOneBySlug($id);

		
		// todo : exceptions

		$em->remove($entity);
		$em->flush();

		$this->recoverTree($em);

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_categories_index'));
    }

	/** 
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function buttonAction(Request $request, $id)
	{
		if ($request->get('add', null)) {
            return $this->redirect($this->generateUrl('eeemarv_categories_new', array('id' => $id)));
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
            return $this->redirect($this->generateUrl('eeemarv_categories_edit', array('id' => $id)));
		}
		if ($request->get('delete', null)) {
            return $this->forward('EeemarvBundle:Category:remove', array('id' => $id));
		}

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_categories_index'));		
	}

	
	private function find($id)
	{
		$entity = $this->repo->find($id);
 
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
		
		return $entity;
	}	
	
		
}
