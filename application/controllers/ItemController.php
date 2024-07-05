<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ItemController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemModel');
    }

    public function create()
    {
        $data = json_decode($this->input->raw_input_stream, true);
        if (!$data) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 400, 'message' => 'No data sent']));
            return;
        }
        try {
            $newItem = $this->ItemModel->addItem($data);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 201, 'message' => 'Item created successfully', 'data' => $newItem]));
        } catch (\Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 500, 'message' => $e->getMessage()]));
        }
    }

    public function update($id = null)
    {
        $data = json_decode($this->input->raw_input_stream, true);
        if (!$data) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 400, 'message' => 'No data sent']));
            return;
        }
        try {
            $updatedItem = $this->ItemModel->editItem($id, $data);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => 200, 'message' => 'Item updated successfully', 'data' => $updatedItem]));
        } catch (\Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 500, 'message' => $e->getMessage()]));
        }
    }
}
