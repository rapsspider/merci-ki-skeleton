<?php
/**
 * @author Jason BOURLARD<jason.bourlard@gmail.com>,
 */

namespace MerciKI\App\Controllers;

use MerciKI\Exception\PageNotExist;
use MerciKI\Exception\NonAdminUtilisateur;

class NewsController extends AppController {
    
    public $layout = "default";
	
	public $models = [
	    'News' => 'PDO'
	];
	
	public function index() {
		$news = $this->News->getList();
        $this->addVar('news', $news);
        
        return $this->view('News\index');
	}

	public function vue() {
		if(!isset($this->request->params['id'])) {
			throw new PageNotExist('La page demandée n\'existe pas');
		}

		try {
		    $new = $this->News->get($this->request->params['id']);
		} catch(MerciKIException $e) {
			throw new PageNotExist('La page demandée n\'existe pas');
		}

        $this->addVar('new', $new);
	}

	public function admin_index() {
		$news = $this->News->getList();
        $this->addVar('news', $news);
        
        return $this->view('News\admin_index');
	}

	public function admin_add() {
		$new = $this->News->newEntity();

		$this->addVar('new', $new);
        
        return $this->view('News\admin_add');
	}

	public function admin_save() {
		$new = $this->News->newEntity();

		if($this->request->data && isset($this->request->data['new'])) {
		    $new->set($this->request->data['new']);
			$ajoute = $this->News->create($new);

			if($ajoute) {
				return $this->redirect('/admin/news/index');
			} // TODO redirection
		}

		$this->addVar('new', $new);
        return $this->view('News\admin_add');
	}

	public function admin_edit() {
		if(!isset($this->request->params['id'])) {
			throw new PageNotExist('La page demandée n\'existe pas');
		}

		try {
		    $new = $this->News->get($this->request->params['id']);
		} catch(MerciKIException $e) {
			throw new PageNotExist('La page demandée n\'existe pas');
		}

		if($this->request->data && isset($this->request->data['new'])) {
			$new->set($this->request->data['new']);
			$modifie = $this->News->edit($new);

			if($modifie) {
				return $this->redirect('/admin/news/index');
			} // TODO redirection
		}

        $this->addVar('new', $new);
	}

    /**
     * Delete the new which have the id passed in request's parameter.
     * @throw PageNotExist     Not entity found with the id.
     * @throw MerciKiException Error during the delete.
     */
	public function admin_delete() {
		try {
		    $new = $this->News->get($this->request->params['id']);
		} catch(MerciKIException $e) {
			throw new EntityNotExist('Entity not exist.');
		}

		if($this->News->delete($new)) {
            return $this->redirect('/admin/news/index');
		}
		throw new MerciKiException('Impossible to delete this image. An error occured ...');
	}

}

?>