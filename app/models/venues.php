<?php

/**
 * Description of Venue 
 *
 * @author Eftakhairul Islam <eftakhairul@gmail.com>
 */

class Venues extends MY_Model
{
    public function  __construct ()
    {
        parent::__construct();
        $this->loadTable('venues', 'venue_id');
    }

    public function save(array $data)
    {
        if(empty($data)) {
            return false;
        }

        return $this->insert($data);
    }

    public function delete($id)
    {
        if (empty($id)) {
            return false;
        }

        return $this->remove($id);
    }

    public function update($data, $id)
    {
        if (empty($data) OR empty($id) ) {
            return false;
        }

        return parent::update($data, $id);
    }

    public function getAllById($id)
    {
        if (empty($id)) {
            return false;
        }

        return $this->findBy('venue_id',$id);
    }

    public function getAll($offset = 0)
    {
        $limit = $this->config->item('rowsPerPage');
        $this->db->select();
        $this->db->from($this->table);
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    public function countAllUsers()
    {
        return $this->db->count_all("{$this->table}");
    }

    public function getAllVenues()
    {
        return $this->findAll(null, "{$this->primaryKey}, title");
    }
}