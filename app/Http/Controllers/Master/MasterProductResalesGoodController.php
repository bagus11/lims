<?php

namespace App\Http\Controllers\Master;

use App\Exports\MasterProductResalesGoodsFormatExport;
use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ResalesGoods\StoreResalesGoodsRequest;
use App\Http\Requests\Master\ResalesGoods\UpdateResalesGoodsRequest;
use App\Imports\Master\UploadMasterProductResalesGoods;
use App\Models\Master\MasterProductResalesGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MasterProductResalesGoodController extends Controller
{
    function index()
    {
        return view('master.master_product_resales_goods.master_product_resales_goods-index');
    }

    function getMasterProductResalesGoods()
    {
        $data = MasterProductResalesGood::orderBy('id', 'desc')->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    function getDetailResalesGoods(Request $request)
    {
        $detail = MasterProductResalesGood::find($request->id);
        return response()->json([
            'detail' => $detail,
        ]);
    }

    function addMasterProductResalesGoods(Request $request, StoreResalesGoodsRequest $storeResalesGoodsRequest)
    {
        // try {
            $storeResalesGoodsRequest->validated();

            $spesification = [];
            if ($request->spesification && $request->spesification != '') {
                $spesification = json_decode($request->spesification, true);
            }

            $post = [
                'name' => $request->name,
                'size' => $request->size ?? '',
                'uom' => $request->uom !== null && trim($request->uom) !== '' ? trim($request->uom) : null,
                'drawing' => $request->drawing !== null && trim($request->drawing) !== '' ? trim($request->drawing) : null,
                'spesification' => $spesification,
            ];

            DB::transaction(function () use ($post) {
                MasterProductResalesGood::create($post);
            });

            return ResponseFormatter::success(
                $post,
                'Master Product Resales Goods successfully added'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Master Product Resales Goods failed to add',
        //         500
        //     );
        // }
    }

    function updateMasterProductResalesGoods(Request $request, UpdateResalesGoodsRequest $updateResalesGoodsRequest)
    {
        // try {
            $updateResalesGoodsRequest->validated();

            $spesification = [];
            if ($request->spesification && $request->spesification != '') {
                $spesification = json_decode($request->spesification, true);
            }

            $post = [
                'name' => $request->name,
                'size' => $request->size ?? '',
                'uom' => $request->uom !== null && trim($request->uom) !== '' ? trim($request->uom) : null,
                'drawing' => $request->drawing !== null && trim($request->drawing) !== '' ? trim($request->drawing) : null,
                'spesification' => $spesification,
            ];

            DB::transaction(function () use ($post, $request) {
                MasterProductResalesGood::find($request->id)->update($post);
            });

            return ResponseFormatter::success(
                $post,
                'Master Product Resales Goods successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Master Product Resales Goods failed to update',
        //         500
        //     );
        // }
    }

    function deleteMasterProductResalesGoods(Request $request)
    {
        // try {
            if (!auth()->user()->canManageModule('view-master_product_resales_goods')) {
                return ResponseFormatter::error(null, 'You are not allowed to manage this master data', 403);
            }

            $product = MasterProductResalesGood::find($request->id);
            if (!$product) {
                return ResponseFormatter::error(null, 'Master Product Resales Goods not found', 404);
            }

            // FK resales_goods.product_id dibuat cascade; tanpa guard ini,
            // hapus 1 master ikut melenyapkan seluruh transaksi historisnya.
            $usedCount = \App\Models\Transaction\Inspection\ResalesGood::where('product_id', $product->id)->count();
            if ($usedCount > 0) {
                return ResponseFormatter::error(
                    null,
                    'Master product is used by ' . $usedCount . ' transaction(s) and cannot be deleted',
                    400
                );
            }

            DB::transaction(function () use ($product) {
                $product->delete();
            });

            return ResponseFormatter::success(
                null,
                'Master Product Resales Goods successfully deleted'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Master Product Resales Goods failed to delete',
        //         500
        //     );
        // }
    }

    function uploadMasterProductResalesGoods(Request $request)
    {
        // try {
            if (!auth()->user()->canManageModule('view-master_product_resales_goods')) {
                return ResponseFormatter::error(null, 'You are not allowed to manage this master data', 403);
            }

            $request->validate([
                'upload_file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $upload = Excel::import(new UploadMasterProductResalesGoods, $request->file('upload_file'));

            return ResponseFormatter::success(
                $upload,
                'Master Product Resales Goods Uploaded Successfully'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Master Product Resales Goods failed to upload',
        //         500
        //     );
        // }
    }

    function downloadFormatResalesGoods()
    {
        $fileName = 'format_master_product_resales_goods_' . date('YmdHis') . '.xlsx';
        return Excel::download(new MasterProductResalesGoodsFormatExport, $fileName);
    }

    function downloadFormatCsvResalesGoods()
    {
        $headers = MasterProductResalesGoodsFormatExport::formatHeadings();
        $rows    = MasterProductResalesGoodsFormatExport::formatRows();

        $fileName = 'format_master_product_resales_goods_' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}
