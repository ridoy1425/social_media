<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Followers;
use App\Models\Page;
use App\Models\FollowPage;
use App\Models\PersonPost;
use App\Models\PagePost;

class socialNetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    // follow a person
    public function followPerson($id)
    {
        $person = User::where('id',$id)->first();
        if($person)
        {
            $user = Auth::user()->id;
            $followers = Followers::where('userid',$user)->where('followedid',$id)->first();
            if($followers)
            {
                $response['status'] = 0;
                $response['message'] = 'you are already following '.$person->fname;
            }
            else
            {
                $follow =  Followers::create([
                    'userid' => $user,
                    'followedid' => $id,
                ]);
                $response['status'] = 1;
                $response['message'] = 'you have sarted to follow '.$person->fname;
            }
        }   
        else
        {
            $response['status'] = 0;
            $response['message'] = 'this person is not valid';
        }     
        
        return response()->json($response);
    }
    // follow a page
    public function followPage($id)
    {
        $page = Page::where('id',$id)->first();
        if($page)
        {
            $user = Auth::user()->id;
            $FollowPage = FollowPage::where('userid',$user)->where('pageid',$id)->first();
            if($FollowPage)
            {
                $response['status'] = 0;
                $response['message'] = 'you are already following this page';
            }
            else
            {
                $follow =  FollowPage::create([
                    'userid' => $user,
                    'pageid' => $id,
                ]);
                $response['status'] = 1;
                $response['message'] = 'you have sarted to follow '.$page->name;
            }
        }   
        else
        {
            $response['status'] = 0;
            $response['message'] = 'this page is not valid';
        }     
        
        return response()->json($response);
    }
    // create a page
    public function pageCreate(Request $request)
    {  
        $pageName = $request->page_name;
        $user = Auth::user()->id;
        $page = Page::where('userid',$user)->where('pagename',$pageName)->first();
        if($page)
        {
            $response['status'] = 0;
            $response['message'] = 'you already have this page';
        }
        else
        {
            $pagecreate =  Page::create([
                'userid' => $user,
                'pagename' => $pageName,
            ]);
            $response['status'] = 1;
            $response['message'] = $pageName.' page Created Successfully';
        }

        return response()->json($response);        
    }
    // post content 
    public function personPost(Request $request)
    {
        $user = Auth::user()->id;
        $post = $request->post_content;

        $post =  PersonPost::create([
            'userid' => $user,
            'post' => $post,
        ]);
        $response['status'] = 1;
        $response['message'] = 'Post has been uploaded Successfully';
        return response()->json($response);
    }
    // post content in a page
    public function pagePost(Request $request,$pageId)
    {
        $post = $request->post_content;
        // return response()->json($pageId);
        $user = Auth::user()->id;
        $page = Page::where('id',$pageId)->where('userid',$user)->first();
        if($page)
        {
            $post =  PagePost::create([
                'pageid' => $pageId,
                'post' => $post,
            ]);
            $response['status'] = 1;
            $response['message'] = 'Post has been uploaded Successfully';       
        }
        else{
            $response['status'] = 0;
            $response['message'] = 'This is not your page';
        }
        
        return response()->json($response);
        
    }
    // person feed
    public function feed(Request $request)
    {
        $user = Auth::user()->id;
        $page = $request->page;
        $pageSize = $request->page_size ;
        $feed = PersonPost::where('userid',$user)->paginate(
            $perPage = $pageSize, $columns = ['*'], $pageName = $page
        );
        return response()->json($feed);
    }
    
}
