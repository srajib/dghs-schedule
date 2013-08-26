<?php

/**
 * Description of Venue Controller
 *
 * @package     Controller
 * @author      Eftakhairul <eftakhairul@gmail.com>
 * @author      Syed Abidur Rahman <aabid048@gmail.com>
 */

include_once APPPATH . "controllers/BaseController.php";
class VenueController extends BaseController
{
    public function  __construct ()
    {
        parent::__construct();
        $this->_ensureLoggedIn();

        $this->data['userType'] = $this->session->userdata('userType');
        $this->load->model('venues');
    }
    
    public function index()
    {
        $this->_checkAdmin();

        $url = site_url('venue');
        $this->processPagination($url);

        $this->layout->view('venue/index', $this->data);
    }

    private function processPagination($url)
    {
        $this->load->library('pagination');

        $uriAssoc = $this->uri->uri_to_assoc();
        $page = empty ($uriAssoc['page']) ? 0 : $uriAssoc['page'];
        $this->data['venues'] = $this->venues->getAll($page);

        $paginationOptions = array(
            'baseUrl' => $url . '/page/',
            'segmentValue' => $this->uri->getSegmentIndex('page') + 1,
            'numRows' => $this->venues->countAllGroups()
        );

        $this->pagination->setOptions($paginationOptions);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->setRulesForAddVenue();
        
        if (!empty ($_POST)) {

            if ($this->form_validation->run()) {

                $this->venues->save($_POST);
                $this->_redirectForSuccess('venue',
                       'The venue has been created successfully.');

            } else {
                $this->data['errorMessage'] = 'Please correct the following errors.';
            }
        }

        $this->layout->view('venue/add-venue', $this->data);
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->setRulesForAddVenue();

        if (!empty ($_POST)) {

            if ($this->form_validation->run()) {

                if ($this->venues->update($_POST, $id)) {
                     $this->_redirectForSuccess('Venue', 'Venue has been updated successfully');
                } else {
                    $this->data['error'] = 'Data is not save';
                }

            } else {
                $this->data['error'] = 'Enter required information.';
                $this->data['venues'] = $_POST;
            }

        } else {
            $this->data['venues'] = $this->venues->getAllById($id);
        }

        $this->layout->view('venue/edit-venue', $this->data);
    }

    public function delete()
    {
        if (!$this->_checkAdmin()) {
            return;
        }

        $data = $this->uri->uri_to_assoc();

        if (empty ($data['id'])) {
            $this->_redirectForFailure('venue', 'Venue id is not found.');
        } else {
            $this->venues->remove($data['id']);
            $this->_redirectForSuccess('venue', 'Venue deletion has been successful.');
        }
    }


    protected function _checkAdmin()
    {
        if ($this->session->userdata('userType') != SUPER_ADMIN) {

            $this->_redirectForFailure('user/manageUser',
                'You are not authorized for this section.'
            );

            return false;
        }

        return true;
    }
}