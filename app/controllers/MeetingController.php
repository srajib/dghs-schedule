<?php

/**
 * Description of Meeting Controller
 *
 * @package     Controller
 * @author      Syed Abidur Rahman <aabid048@gmail.com>
 */

include_once APPPATH . "controllers/BaseController.php";
class MeetingController extends BaseController
{
    public function  __construct ()
    {
        parent::__construct();
        $this->_ensureLoggedIn();

        $this->data['userType'] = $this->session->userdata('userType');
        $this->load->model('venues_meetings');
    }
    
    public function index()
    {

        if(empty($_POST['venue_id'])) {
            $this->session->unset_userdata('venue_id');
        } else {
            $this->session->set_userdata('venue_id', $this->input->post('venue_id'));
        }
        
        $url = site_url('meeting');
        $this->processPagination($url);

        $this->data['refreshFlag'] = 1;
        $this->load->model('venues');
        $this->data['venues'] = $this->venues->getAll();
        $this->layout->view('meeting/index', $this->data);
    }

    private function processPagination($url)
    {
        $this->load->library('pagination');

        $uriAssoc = $this->uri->uri_to_assoc();
        $page = empty ($uriAssoc['page']) ? 0 : $uriAssoc['page'];
        $this->data['meetings'] = $this->venues_meetings->getAll($page);

        $paginationOptions = array(
            'baseUrl' => $url . '/page/',
            'segmentValue' => $this->uri->getSegmentIndex('page') + 1,
            'numRows' => $this->venues_meetings->countAllGroups()
        );

        $this->pagination->setOptions($paginationOptions);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->load->model('venues_meetings');
        $this->form_validation->setRulesForAddMeeting();

        if (!empty ($_POST)) {

            if ($this->form_validation->run()) {

//                if ($this->venues_meetings->CheckMeeting($_POST)) {
//                    $this->data['errorMessage'] = 'Schedule is already there';
//                } else {
                    $this->venues_meetings->save($_POST);
                    $this->_redirectForSuccess('meeting',
                           'A meeting of a venue has been created successfully.');
               // }
            } else {
                $this->data['errorMessage'] = 'Please correct the following errors.';
            }
        }

        $this->load->model('venues');
        $this->load->model('groups');
        $this->data['venues'] = $this->venues->getAll();
        $this->data['groups'] = $this->groups->getAll();
        $this->layout->view('meeting/add', $this->data);
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->setRulesForAddMeeting();

        if (!empty ($_POST)) {

            if ($this->form_validation->run()) {

                if ($this->venues_meetings->CheckMeeting($_POST)) {
                     $this->data['errorMessage'] = 'Schedule is already there';
                } else{

                    if ($this->venues_meetings->update($_POST, $id)) {
                         $this->_redirectForSuccess('meeting', 'Venue has been updated successfully');
                    } else {
                        $this->data['error'] = 'Data is not save';
                    }
                }

            } else {
                $this->data['error'] = 'Enter required information.';
                $this->data['meetings'] = $_POST;
            }

        } else {

            $result = $this->venues_meetings->getAllById($id);
            $this->data['meetings'] = $this->processingDateTime($result);
        }

        $this->load->model('venues');
        $this->load->model('groups');
        $this->data['venues'] = $this->venues->getAll();
        $this->data['groups'] = $this->groups->getAll();
        $this->layout->view('meeting/edit', $this->data);
    }

    public function delete()
    {
        $data = $this->uri->uri_to_assoc();

        if (empty ($data['id'])) {
            $this->_redirectForFailure('venue', 'Venue id is not found.');
        } else {
            $data['is_cancelled'] = 1;
            $this->venues_meetings->update($data, $data['id']);
            $this->_redirectForSuccess('venue', 'Venue deletion has been successful.');
        }
    }

    public function viewMeeting()
    {
         $data = $this->uri->uri_to_assoc();

        if (empty ($data['id'])) {
            $this->_redirectForFailure('meeting', 'meeting is not found');
        } else {
            $result = $this->venues_meetings->getDetailByMeetingId($data['id']);
            $this->data['meeting'] = $this->processingDateTime($result);
            $this->layout->view('meeting/view-meeting', $this->data);
        }
        
    }

    public function printMeeting()
    {
        $this->load->model('venues_meetings');
        $this->data['meetings'] = $this->venues_meetings->printAll();
        $this->layout->view('meeting/print-meeting', $this->data);
    }

    private function processingDateTime($data)
    {
        if(empty($data)) $this->_redirectForFailure('meeting', 'No data has been found.');
        $starting = explode(" ",$data['starting_date_time']);
        $Ending = explode(" ",$data['ending_date_time']);
        $data['startingDate'] = DateHelper::mysqlToHuman($starting[0]);
        $data['startingTime'] = date("g:i a", STRTOTIME($starting[1]));
        $data['endingDate'] = DateHelper::mysqlToHuman($Ending[0]);
        $data['endingTime'] = date("g:i a", STRTOTIME($Ending[1]));

        return $data;
    }

}