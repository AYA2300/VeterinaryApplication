<?php

namespace App\Http\Traits;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{

    /**
     * Generate custom API response.
     *
     * This method generates a JSON response for API requests. It supports paginated data
     * and includes pagination details if the provided data is an instance of
     * \Illuminate\Pagination\LengthAwarePaginator.
     *
     * @param  mixed  $data    The response data. It can be any data type.
     * @param  string $message A message describing the response.
     * @param  int    $status_code  The HTTP status code for the response.
     * @return \Illuminate\Http\JsonResponse The API response in JSON format.
     */
    public function send_response(mixed $data, string $message, int $status_code)
    {
        $success_state = $status_code == 200 ? true : false;

        $response = [
            'success'   => $success_state,
            'data'      => $data,
            'message'   => $message
        ];

        return response()->json($response, $status_code);
    }



    /**
     * Generate paginated data from a collection.
     *
     *
     * @param  Collection  $collection  The collection of data.
     * @param  int    $per_page  The number of records per page.
     * @return mixed array contains the data and meta data for pagination.
     */

    protected function paginate(Collection $collection, $per_page = 10)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        $results = $collection->slice(($page - 1) * $per_page, $per_page)->values();

        $data = new LengthAwarePaginator($results, $collection->count(), $per_page, $page, [
            'path'  =>  LengthAwarePaginator::resolveCurrentPath(),
        ]);

        // $paginated->appends(request()->all());

        $paginated['data']    =   $data ;

        $paginated['meta']    =   [
            'total_elements'    =>  $data->total(),
            'total_pages'       =>  $data->lastPage(),
            'current_page'      =>  $data->currentPage(),
            'first_page_url'    =>  $data->url(1),
            'next_page_url'     =>  $data->nextPageUrl() === null ? '' : $data->nextPageUrl(),
            'prev_page_url'     =>  $data->previousPageUrl() === null ? '' : $data->previousPageUrl(),
            'last_page_url'     =>  $data->url($data->lastPage()),
        ];

        return $paginated;
    }


    // Note:
    /**
     * Using paginate function in api response.
     *
     *  $paginated  =   paginate(data_collection, 10);
     *  $paginated_data    =   $paginated['data']; // we can use the paginated_data in resources
     *  $paginated_meta    =   $paginated['meta'];
     *
     *  $output = ['data' => $paginated_data, 'meta' => $paginated_meta]
     *
     */
}
?>
