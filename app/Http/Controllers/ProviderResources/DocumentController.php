<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Document;
use App\ProviderDocument;
use App\Provider;
use App\ProviderService;
use App\User;
use Exception;
use GPBMetadata\Google\Api\Expr\V1Beta1\Expr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Setting;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $VehicleDocuments = Document::vehicle()->get();
        $DriverDocuments = Document::driver()->get();

        $Provider = Auth::guard('provider')->user();

        return view('provider.document.index', compact('DriverDocuments', 'VehicleDocuments', 'Provider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Requests and lists the specified document related to the autheticated
     * Provider.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Display the current necessary documents to the current
     * authenticated provider
     * 
     * @type `GET`
     * @endpoint `/provider/document`
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        /**
         * @var \App\User $user the current user's instance
         */
        $user = Auth::user();

        if ($user) {
            try {
                $service = ProviderService::select('service_types.name', 'service_number', 'service_model')
                    ->leftjoin('service_types', 'service_types.id', '=', 'provider_services.service_type_id')
                    ->where('provider_id', $user->id)->first();

                $documents = Document::select('id', 'name', 'type')
                    ->with(['details' => function ($query) use ($user) {
                        $query->where('provider_id', $user->id);
                    }])
                    ->where('shown_to', $service->name)
                    ->get();


                return response()->json($documents);
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            throw new Exception('User not found', 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Updates the provider's document file to be validated by the host
     *
     * @type `PUT|PATCH`
     * @endpoint `provider/documents/{docTypeId}`
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'document' => 'mimes:jpg,jpeg,png',
        ]);

        //Log::info($request->all());

        try {

            $Document = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)
                ->where('document_id', $id)->with('provider')->with('document')
                ->firstOrFail();

            Storage::delete($Document->url);

            $filename = str_replace(" ", "", $Document->document->name);

            $ext = $request->file('document')->guessExtension();

            $path = $request->file('document')->storeAs(
                "provider/documents/" . $Document->provider_id,
                $filename . '.' . $ext
            );

            $Document->update([
                'url' => $path,
                'status' => 'ASSESSING',
            ]);
        } catch (ModelNotFoundException $e) {

            $document = Document::find($id);
            $filename = str_replace(" ", "", $document->name);
            $ext = $request->file('document')->guessExtension();
            $path = $request->file('document')->storeAs(
                "provider/documents/" . \Auth::guard('provider')->user()->id,
                $filename . '.' . $ext
            );
            ProviderDocument::create([
                'url' => $path,
                'provider_id' => \Auth::guard('provider')->user()->id,
                'document_id' => $id,
                'status' => 'ASSESSING',
            ]);
        }



        //update document to card status
        $total = Document::count();
        $provider_total = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)->count();

        if ($total == $provider_total) {
            if (config('constants.card', 0) == 1) {
                Provider::where('id', \Auth::guard('provider')->user()->id)->where('status', 'document')->update(['status' => 'onboarding']);
            } else {
                if (Setting::get('demo_mode', 0) == 1) {
                    Provider::where('id', \Auth::guard('provider')->user()->id)->where('status', 'document')->update(['status' => 'approved']);
                } else {
                    Provider::where('id', \Auth::guard('provider')->user()->id)->where('status', 'document')->update(['status' => 'onboarding']);
                }
            }
        }

        return back();
    }

    public function documentupdate($image, $id, $provider_id, string $ext = null)
    {
        try {
            $Document = ProviderDocument::where('provider_id', $provider_id)
                ->where('document_id', $id)->with('provider')->with('document')
                ->firstOrFail();

            Storage::delete($Document->url);

            $filename = str_replace(" ", "", $Document->document->name);

            if (!$ext)
                $ext = $image->guessExtension();
            $path = $image->storeAs(
                "provider/documents/" . $Document->provider_id,
                $filename . '.' . $ext
            );

            $Document->update([
                'url' => $path,
                'status' => 'ASSESSING',
            ]);
        } catch (ModelNotFoundException $e) {

            $document = Document::find($id);
            $filename = str_replace(" ", "", $document->name);
            $ext = $image->guessExtension();
            $path = $image->storeAs(
                "provider/documents/" . $provider_id,
                $filename . '.' . $ext
            );
            ProviderDocument::create([
                'url' => $path,
                'provider_id' => $provider_id,
                'document_id' => $id,
                'status' => 'ASSESSING',
            ]);
        }

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
