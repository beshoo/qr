<?php

namespace App\Http\Controllers;

use App\Library\Zbar;
use App\Models\qr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class QrController extends Controller
{
    private $qr_typs = [
        'cryptoQr',
        'audio',
        'video',
        'pdf',
        'whatsapp',
        'image',
        'url',
        'email',
        'sms',
        'vcard',
        'mecard',
        'google-reviews',
        'googlemap',
        'twitter',
        'youtube',
        'facebook',
        'wifi',
        'event',
    ];

    public function index()
    {
        $paginator = auth()->user()->qr()->paginate(10);
        $paginator->getCollection()->transform(function ($value) {
            $value->thumbnail = $this->pathToUrl($value->thumbnail);

            return $value;
        });

        return response()->json([
            'success' => true,
            'qr_list' => $paginator,

        ]);
    }

    private function pathToUrl($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = str_replace(str_replace('\\', '/', dirname(base_path(), 1)), '', $path);
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', $path);

        return env('APP_URL').$url;
    }

    public function show(Request $request)
    {
        // validate Qr_ID
        $qr_code = $this->checkQrCodeId($request->qr_id);
        if (! $qr_code) {
            return response()->json(['message' => 'Incorrect Qr id.', 'success' => false]);
        }

        return response()->json($qr_code);
    }

    private function checkQrCodeId($qr_id)
    {
        $qr_code = qr::where('qr_id', '=', $qr_id)->first();
        if (! $qr_code) {
            return false;
        }

        return $qr_code;
    }

    public function store(Request $request)
    {
        /** @var Generate Qr ID $qr_id */
        $qr_id = $this->qr_id();

        $attributes = request()->validate([
            'title' => 'required',
            'type' => 'required|in:'.implode(',', $this->qr_typs),
            'thumbnail' => ['required', 'image'],
            'json' => 'json|required',
        ]);

        $path = $this->uploadThumbnail();

        $zbar = $this->zbarCheck($path);
        if (! is_bool($zbar)) {
            File::delete($path);

            return response()->json(['message' => $zbar, 'success' => false]);
        }

        qr::create((array_merge($attributes, [
            'user_id' => request()->user()->id,
            'thumbnail' => $path,
            'qr_id' => $qr_id,
        ])));

        return response()->json([
            'message' => 'Qrcode Created successfully',
            'qr_id' => $qr_id,
            'success' => true,
        ]);
    }

    /**
     * @return mixed
     */
    private function qr_id()
    {
        while (true) {
            $qr_id = Str::random(8);
            if (! qr::where('qr_id', '=', $qr_id)->first('id')) {
                break;
            }
        }

        return $qr_id;
    }

    public function uploadThumbnail()
    {
        $file = request()->file('thumbnail');
        $fileName = date('mdYHis').'-'.uniqid().$file->getClientOriginalName();
        $destinationPath = public_path().'/images/qr_thumbnails';
        $file->move($destinationPath, $fileName);
        $path = $destinationPath.'/'.$fileName;

//        $image = new Imagine();
//        $image->open($path)->resize(new Box(300, 300))->save($path, ['jpeg_quality' => 100]);
//
//        $manager = new ImageManager(['driver' => 'imagick']);
//        $image = $manager->make($path)->resize(100, 100);
//        $image->save($path, 100);

        return $path;
    }

    private function zbarCheck($path)
    {
        try {
            //composer require tarfin-labs/zbar-php
            $zbar = new Zbar($path);
            $code = $zbar->scan();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Qr $qr, Request $request)
    {
        $attributes = request()->validate([
            'title' => 'required',
            'type' => 'required|in:'.implode(',', $this->qr_typs),
            'thumbnail' => ['image'],
            'json' => 'json|required',
        ]);

        $qr_code = $this->checkQrCodeId(request()->qr_id);
        if (! $qr_code || $qr_code->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Incorrect Qr id.', 'success' => false]);
        }

        if ($attributes['thumbnail'] ?? false) {
            $path = $this->uploadThumbnail();
            $zbar = $this->zbarCheck($path);
            if (! is_bool($zbar)) {
                File::delete($path);

                return response()->json(['message' => $zbar, 'success' => false]);
            }
            File::delete($attributes['thumbnail']);
            $attributes['thumbnail'] = $path;
        }
        qr::where('qr_id', request()->qr_id)->update($attributes);

        return response()->json(['message' => 'Qr Updated Successfully.', 'success' => true]);
    }

    public function destroy(Request $request)
    {
        $qr_code = $this->checkQrCodeId(request()->qr_id);
        if (! $qr_code || $qr_code->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Incorrect Qr id.', 'success' => false]);
        }

        File::delete($qr_code['thumbnail']);
        qr::where('qr_id', request()->qr_id)->delete();

        return response()->json(['message' => 'Qr deleted Successfully.', 'success' => true]);
    }

    public function detect(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            $path = $this->uploadThumbnail();
            $zbar = $this->zbarCheck($path);
            File::delete($path);
            if ($zbar && is_bool($zbar)) {
                return response()->json(['message' => 'valid_qr', 'success' => true]);
            } else {
                return response()->json(['message' => $zbar, 'success' => false]);
            }
        }
    }

//    protected function validateQr(?Qr $qr = null): array
//    {
//        $qr ??= new Qr();
//
//        return request()->validate([
//            'title' => 'required',
//            'thumbnail' => $qr->exists ? ['image'] : ['required', 'image'],
//            'json' => 'json|required',
//        ]);
//    }
}
