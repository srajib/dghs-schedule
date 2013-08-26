<?php

/**
 * Description of Venues Meetings
 *
 * @author      Eftakhairul Islam <eftakhairul@gmail.com>
 * @author      Syed Abidur Rahman <aabid048@gmail.com>
 */

class Venues_meetings extends MY_Model
{
    public function  __construct ()
    {
        parent::__construct();
        $this->loadTable('venues_meetings', 'venue_meeting_id');
    }

    /**
     * Return all meeting venue based on Page
     *
     * @param int $offset
     * @return mixed
     */
    public function getAll($offset = 0)
    {
        $groups = 'groups';
        $venues = 'venues';

        $this->db->select();
        $this->db->from($this->table);
        $this->db->join($groups, "{$groups}.group_id={$this->table}.group_id");
        $this->db->join($venues, "{$venues}.venue_id={$this->table}.venue_id");
        $this->db->where('starting_date_time >=', date('Y-m-d H:i:s'));
        $this->db->where('is_cancelled', 0);

        $CI =& get_instance();
        $venueId = $CI->session->userdata('venue_id');
        if( !empty($venueId) ) {
            $this->db->where('venues.venue_id', $venueId);
        }

        $this->db->orderby("{$this->table}.starting_date_time ASC");
        $this->db->limit($this->config->item('rowsPerPage'), $offset);
        return $this->db->get()->result_array();
    }

    public function printAll()
    {
        $groups = 'groups';
        $venues = 'venues';

        $this->db->select();
        $this->db->from($this->table);
        $this->db->join($groups, "{$groups}.group_id={$this->table}.group_id");
        $this->db->join($venues, "{$venues}.venue_id={$this->table}.venue_id");
        $this->db->where('starting_date_time >=', date('Y-m-d H:i:s'));
        $this->db->where('is_cancelled', 0);

        $CI =& get_instance();
        $venueId = $CI->session->userdata('venue_id');
        if( !empty($venueId) ) {
            $this->db->where('venues.venue_id', $venueId);
        }

        $this->db->limit(10, 0);
        return $this->db->get()->result_array();
    }

    /**
     * Return the total count of meeting venue
     *
     * @return int
     */
    public function countAllGroups()
    {
        $CI      =& get_instance();
        $venueId = $CI->session->userdata('venue_id');

        $this->db->select("count(venue_meeting_id) as cnt");
        $this->db->from($this->table);
        $this->db->where('starting_date_time >=', date('Y-m-d H:i:s'));

        if( !empty($venueId) ) {
            $this->db->where('venue_id', $venueId);
        }

        $result =  $this->db->get()->row();

        return empty($result)? 0:(int)$result->cnt;
    }

    public function getDetailByMeetingId($meetingId)
    {
        $this->db->select();
        $this->db->from($this->table);
        $this->db->join('groups', "groups.group_id={$this->table}.group_id");
        $this->db->join('venues', "venues.venue_id={$this->table}.venue_id");
        $this->db->where("{$this->table}.{$this->primaryKey}", $meetingId);

        return $this->db->get()->row_array();
    }

    public function validateUser($data)
    {
        if (!empty ($data['password'])) {
            $data['password'] = md5($data['password']);
        }

        return $this->find($data, 'username, user_type_id, group_id, user_id');
    }

    public function checkUsernameExisted($username)
    {
        $result = $this->find(array('username' => $username), $this->primaryKey);
        return !empty($result);
    }

    public function save(array $data)
    {
       if (!empty ($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        
        $data['created_date'] = date('Y-m-d');
        $data['starting_date_time'] = DateHelper::humanToMysql($data['startingDate'])." ".date("H:i", strtotime($data['startingTime']));
        $data['ending_date_time'] = DateHelper::humanToMysql($data['endingDate'])." ".date("H:i", strtotime($data['endingTime']));

        return $this->insert($data);
    }

    public function modify(array $data)
    {
        if (!empty ($data['password'])) {
            $data['password'] = md5($data['password']);
        }

        return $this->update($data, $data['user_id']);
    }

    public function getUserTypes()
    {
        $this->db->select('*');
        $this->db->from('user_types');
        return $this->db->get()->result_array();
    }

    public function CheckMeeting($data)
    {
        $data['starting_date_time'] = DateHelper::humanToMysql($data['startingDate'])." ".date("H:i", strtotime($data['startingTime']));
        $data['ending_date_time'] = DateHelper::humanToMysql($data['endingDate'])." ".date("H:i", strtotime($data['endingTime']));

        $sql = "SELECT * FROM `{$this->table}`
                WHERE `venue_id` = '{$data['venue_id']}' AND (('{$data['starting_date_time']}' BETWEEN `starting_date_time` AND `ending_date_time`)
                OR ('{$data['ending_date_time']}' BETWEEN `starting_date_time` AND `ending_date_time`))
                AND `is_cancelled` = 0";

        $result = $this->db->query($sql)->row_array();

        return !empty($result);
    }

    public function getAllById($meetingId)
    {
        $this->db->select();
        $this->db->from($this->table);
        $this->db->where("{$this->table}.{$this->primaryKey}", $meetingId);

        return $this->db->get()->row_array();
    }
}