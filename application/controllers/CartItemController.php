<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CartItemController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CartItemModel');
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
            $newCartItem = $this->CartItemModel->addItemToCart($data);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 201, 'message' => 'Item added to cart successfully', 'data' => $newCartItem]));
        } catch (\Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 500, 'message' => $e->getMessage()]));
        }
    }
}
