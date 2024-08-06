<?php 
namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Hash;
use Illuminate\Http\Request;
use Lang;
use Redirect;
use Sentinel;
use App\Models\EmailTemplate;
use URL;
use View;
use Validator;
use DataTables;
use App\Http\Controllers\Controller;

class Email_templateController extends Controller
{
     
     public function create()
    {
        return view('admin.emailTemplates.addtemplate');
    }
   
    public function store(Request $request)
    {  
        $this->validate($request, ['description'  => 'required']);
        $data = $request->input();
        $EmailTemplatecontent = new EmailTemplate();
        $EmailTemplatecontent->alias = $data['alias'];
        $EmailTemplatecontent->allowed_vars = $data['allowed_vars'];
        $EmailTemplatecontent->subject = $data['subject'];
        $EmailTemplatecontent->title = $data['title'];
        $EmailTemplatecontent->from = $data['from'];
        $EmailTemplatecontent->description = $data['description'];
         $EmailTemplatecontent->status = 1;
        $EmailTemplatecontent->save();
        $success = Lang::get('EmailTemplate has been successfully saved.');
        return  redirect('admin/emailTemplates/')->with('success', $success);
       
    }

    public function index()
    {

        $emailTemplates = EmailTemplate::where("status",1)->get();
        return view('admin.emailTemplates.index', compact('emailTemplates'));
    }


    public function edit($id)
    {
        $emailTemplate = EmailTemplate::where('id',$id)->first();
        if (empty($emailTemplate)) {
            return redirect(route('admin.emailTemplates.index'));
        }
        return view('admin.emailTemplates.edit')->with('emailTemplate', $emailTemplate);
    }

    public function update($id, Request $request)
    {
        $emailTemplate = EmailTemplate::where('id',$id)->first();
        if (empty($emailTemplate)) {

            return redirect(route('admin.emailTemplates.index'));
        }

        $res = EmailTemplate::find($id);
        $res->subject = $request->subject;
        $res->title = $request->title;
        $res->from = $request->from;
        $res->description = $request->description;
        $res->save();
        return redirect(route('admin.emailTemplates.index'))->with('success', "Update Successfully");
    }


    
}
