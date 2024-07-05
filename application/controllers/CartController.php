<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CartController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CartModel');
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
            $newCart = $this->CartModel->addCart($data);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 201, 'message' => 'Cart created successfully', 'data' => $newCart]));
        } catch (\Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 500, 'message' => $e->getMessage()]));
        }
    }

    public function checkout($id = null)
    {
        try {
            $cartItems = $this->CartModel->checkout($id);
            $total = array_reduce($cartItems, function ($carry, $item) {
                return $carry + $item->price * $item->quantity;
            }, 0);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => 200, 'message' => 'Cart checked out successfully', 'data' => ['id' => $id, 'items' => $cartItems, 'total' => $total]]));
        } catch (\Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 500, 'message' => $e->getMessage()]));
        }
    }

    public function history($userId = null)
    {
        try {
            $history = $this->CartModel->getShoppingHistory($userId);
            foreach ($history as &$cart) {
                $cart->total = array_reduce($cart->items, function ($carry, $item) {
                    return $carry + $item->price * $item->quantity;
                }, 0);
            }
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => 200, 'message' => 'Shopping history retrieved successfully', 'data' => $history]));
        } catch (\Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 500, 'message' => $e->getMessage()]));
        }
    }
}
