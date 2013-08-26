<?php

/**
 * Description of Schedule Controller
 *
 * @package     Controller
 * @author      Eftakhairul <eftakhairul@gmail.com>
 * @author      Syed Abidur Rahman <aabid048@gmail.com>
 */
include_once APPPATH . "controllers/BaseController.php";
class ScheduleController extends BaseController
{
    public function  __construct ()
    {
        parent::__construct();
        $this->_ensureLoggedIn();

        $this->load->library('pagination');
        $this->load->model('schedules');
        $this->data['userType'] = $this->session->userdata('userType');
    }
    
    public function index()
    {
        $this->load->model('groups');
        $uriAssoc = $this->uri->uri_to_assoc();
        $uriAssoc = array_merge($uriAssoc, array(
                        'url' => site_url('schedule/index'),
                        'status' => 'due'
                   ));

        $this->_processPagination($uriAssoc);
        $this->data['flagForPrint'] = true;
        $this->data['refreshFlag'] = 1;
        $this->data['groups'] = $this->groups->getAll();

        $this->layout->view('schedule/index', $this->data);
    }

    public function printSchedule()
    {
        $this->load->model('groups');
        $this->load->helper('table_row');
        $options['group_id'] = $this->session->userdata('groupType');
        $this->data['schedules'] = $this->sortDataAsGroupByDate($this->schedules->getAllForPrint($options));
        $this->data['title'] = $this->groups->getPrintTitle($this->session->userdata('groupType'));

        $this->load->view('schedule/print-schedule', $this->data);
    }

    private function sortDataAsGroupByDate($data)
    {
        $schedules = array();

        foreach($data AS $row)
        {
            $toDate = $row['to_date'];
            if(!empty($toDate) AND ($toDate != $row['date'])) {
                $joinDate = DateHelper::mysqlToHuman($row['date'])
                            .' '. date('l', strtotime($row['date']))
                            .'<br/>' .'To ' . DateHelper::mysqlToHuman($toDate) . ' ' .date('l', strtotime($toDate)). '</td>';
                $schedules[$joinDate][] = $row;
            } else {
                $joinDate = DateHelper::mysqlToHuman($row['date']) .  '<br/>' . date('l', strtotime($row['date'])) . '</td>';
                $schedules[$joinDate][] = $row;
            }
        }

        return $schedules;
    }

    public function viewSchedules()
    {
        $uriAssoc = $this->uri->uri_to_assoc();
        $uriAssoc = array_merge($uriAssoc, array(
                        'url' => site_url('schedule/viewSchedules'),
                        'isPast' => true
                   ));
        $this->_processPagination($uriAssoc);

        $this->layout->view('schedule/index', $this->data);
    }

    public function export()
    {
        $this->load->model('groups');
        $this->data['groups'] = $this->groups->getAll();
        $this->layout->view('schedule/export', $this->data);
    }

    public function finalExport()
    {
        if (empty($_POST['group_id']) AND
            $this->session->userdata('groupType') AND
            ($this->session->userdata('userType') != SUPER_ADMIN) ) {
            $_POST['group_id'] = $this->session->userdata('groupType');
        }

        $schedules = $this->schedules->pastschedule($_POST, $_POST);
        $this->excelCommonHeader();
        $this->load->library('Exportexcel');

        $this->exportexcel->xlsBOF();

        if (empty($_POST['group_id'])) {
            $this->exportexcel->xlsWriteLabel(0,3, "All Groups");
        } else {
            $this->load->model('groups');
            $title = $this->groups->getPrintTitle($_POST['group_id']);
            $this->exportexcel->xlsWriteLabel(0,3, $title);
        }

        $this->exportexcel->xlsWriteLabel(1,0, "Sl.");
        $this->exportexcel->xlsWriteLabel(1,1, "Group Title");
        $this->exportexcel->xlsWriteLabel(1,2, "Title");
        $this->exportexcel->xlsWriteLabel(1,3, "Description");
        $this->exportexcel->xlsWriteLabel(1,4, "Date");
        $this->exportexcel->xlsWriteLabel(1,5, "Is Date Confirmed");
        $this->exportexcel->xlsWriteLabel(1,6, "Time");
        $this->exportexcel->xlsWriteLabel(1,7, "Is Time Confirmed");
        $this->exportexcel->xlsWriteLabel(1,8, "As Grace");
        $this->exportexcel->xlsWriteLabel(1,9, "Venue");

        $xlsRow = 2;
        foreach($schedules AS $row)
        {
           $this->exportexcel->xlsWriteNumber($xlsRow,0, $xlsRow);
           $this->exportexcel->xlsWriteLabel($xlsRow,1, $row['group_title']);
           $this->exportexcel->xlsWriteLabel($xlsRow,2, $row['title']);
           $this->exportexcel->xlsWriteLabel($xlsRow,3, $row['description']);
           $this->exportexcel->xlsWriteLabel($xlsRow,4, date('d-m-Y', strtotime($row['date'])));
           $this->exportexcel->xlsWriteLabel($xlsRow,5, (empty($row['is_date_not_confirmed'])? 'No':'Yes'));
           $this->exportexcel->xlsWriteLabel($xlsRow,6, date("g:i a", STRTOTIME($row['time'])));
           $this->exportexcel->xlsWriteLabel($xlsRow,7, (empty($row['is_time_not_confirmed'])? 'No':'Yes'));
           $this->exportexcel->xlsWriteLabel($xlsRow,8, $row['grace']);
           $this->exportexcel->xlsWriteLabel($xlsRow,9, $row['venue']);
           $xlsRow++;
        }

        $this->exportexcel->xlsEOF();
    }

    private function _processPagination($options)
    {
        $this->load->library('pagination');

        $options['page'] = empty ($options['page']) ? 0 : $options['page'];

        if($this->session->userdata('userType') != SUPER_ADMIN) {
            $options['group_id'] = $this->session->userdata('groupType');
        }
        $this->data['schedules'] = $this->schedules->getAll($options);

        $paginationOptions = array(
            'baseUrl' => $options['url'] . (empty ($options['status']) ? '' : '/status/'.$options['status']). '/page/',
            'segmentValue' => $this->uri->getSegmentIndex('page') + 1,
            'numRows' => $this->schedules->countAllSchedules($options)
        );

        $this->pagination->setOptions($paginationOptions);
    }

    public function createSchedule()
    {
        $this->_checkAdmin();

        $this->load->model('statuses');
        $this->load->library('form_validation');
        $this->form_validation->setRulesForCreateSchedule();

        if (!empty ($_POST)) {
            
            if ($this->form_validation->run()) {

                $_POST['group_id'] = $this->session->userdata('groupType');
                if ($this->schedules->save($_POST)) {
                   
                     $this->_redirectForSuccess('schedule', 'Event has been created successfully');
                } else {
                    $this->data['error'] = 'Data is not saved.';
                }

            } else {
                $this->data['error'] = 'Enter required information.';
            }
        }

        $this->data['statuses'] = $this->statuses->getAll();
        $this->layout->view('schedule/create', $this->data);
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->setRulesForCreateSchedule();

        $this->load->model('statuses');
        $this->data['statuses'] = $this->statuses->getAll();

        if (!empty ($_POST)) {

            if ($this->form_validation->run()) {

                $_POST['group_id'] = $this->session->userdata('groupType');
                if ($this->schedules->update($_POST, $id)) {
                     $this->_redirectForSuccess('schedule', 'Event has been updated successfully');
                } else {
                    $this->data['error'] = 'Data is not save';
                }

            } else {
                $this->data['error'] = 'Enter required information.';
                $this->data['schedules'] = $_POST;
            }

        } else {
            $this->data['schedules'] = $this->schedules->getAllById($id);
        }

        $this->layout->view('schedule/edit', $this->data);
    }

    public function deleteEvent()
    {
        if (!$this->_checkAdmin()) {
            return;
        }

        $data = $this->uri->uri_to_assoc();

        if (empty ($data['id'])) {
            $this->_redirectForFailure('schedule', 'Event is not found');
        } else {
            $status = array('status_id' => 3);
            $this->schedules->update($status, $data['id']);
            $this->_redirectForSuccess('schedule', 'Event is deleted successfully');
        }
    }

    public function viewEvent($id)
    {
        if(empty($id)) {
            $this->_redirectForFailure('schedule', 'Date is not found');
        }

        $this->data['schedule'] = $this->schedules->getDetailById($id);
        $this->layout->view('schedule/view-event', $this->data);
    }

    public function searchByGroup()
    {
        $this->load->model('groups');
        $uriAssoc = $this->uri->uri_to_assoc();
        $uriAssoc = array_merge($uriAssoc, array(
                        'url' => site_url('schedule/index'),
                        'status' => 'due'
                   ));
        if (!empty ($_POST)) {
             $uriAssoc['group_id'] = $_POST['group_id'];
         }

        $this->_processPagination($uriAssoc);
        $this->data['groups'] = $this->groups->getAll();

        $this->layout->view('schedule/searchbygroupname', $this->data);
        
    }

    public function pastSchedule()
    {
        $this->load->model('groups');
        $options['group_id'] = $this->session->userdata('groupType');

        if(!empty($_POST)) {
            $this->data['schedules'] = $this->schedules->pastschedule($options, $_POST);
        } else {
            $this->data['schedules'] = $this->schedules->pastschedule($options);
        }

        $this->layout->view('schedule/past-schedule', $this->data);
    }

    protected function _checkAdmin()
    {
        if ($this->session->userdata('userType') != SUPER_ADMIN AND $this->session->userdata('userType') != ADMIN) {
            $this->_redirectForFailure('schedule',
                'You are not authorized for this section.'
            );
            return false;
        }
        return true;
    }

    private function excelCommonHeader()
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=report.xls");
        header("Content-Transfer-Encoding: binary ");
    }
}