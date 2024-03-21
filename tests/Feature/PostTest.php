<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpParser\Node\Expr\AssignOp\Pow;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    
    
    public function testSavePost()
    {
        $post=new Post();
        $post->title='learn sass';
        $post->content='sass nadya';
        $post->slug='learn_sass';
        $post->active=true;
        $post->save();

        $this->assertDatabaseHas('posts',[
            'title'=>'learn sass',

        ]);

        // $response = $this->get('/');
        // $response->assertStatus(200);
    }

    public function testPostStoreValid(){

        $data=[
            'title'=>'learn tilwan',
            'content'=>'tilwan nadya',
            'slug'=>'learn_tilwan',
            'active'=>true,
        ];

        $this->post('/posts',$data)->assertStatus(302)->assertSessionHas('status');
        $this->assertEquals(session('status'),'post was created');
        // $post=Post::create($data);

    }

    public function testPostStoreFail(){

        $data=[
            'title'=>'',
            'content'=>'',
        ];
        $this->post('/posts',$data)->assertStatus(302)->assertSessionHas('errors');
        $message=session('errors')->getMessages();
        // dd($message);
        $this->assertEquals($message['title'][0],'The title field is required.');
        $this->assertEquals($message['content'][0],'The content field is required.');

    }

    public function testPostUpdate(){
        $post=new Post();
        $post->title='second learn sass';
        $post->content='second sass nadya';
        $post->slug='learn_sass';
        $post->active=false;
        $post->save();

        $this->assertDatabaseHas('posts',$post->toArray());

        $data=[
            'title'=>'learn tilwan updated',
            'content'=>'tilwan nadya',
            'slug'=>'learn_tilwan',
            'active'=>true,
        ];

        $this->put("/posts/{$post->id}",$data)->assertStatus(302)->assertSessionHas('success');
        $this->assertDatabaseHas('posts',[
            'title'=>$data['title'],
        ]);
        $this->assertDatabaseMissing('posts',[
            'title'=>$post['title'],
        ]);
    }

    public function testPostDelete(){

        $post=new Post();
        $post->title='second learn sass';
        $post->content='second sass nadya';
        $post->slug='learn_sass';
        $post->active=false;
        $post->save();

        $this->assertDatabaseHas('posts',$post->toArray());

        $this->delete("/posts/{$post->id}")->assertStatus(302)->assertSessionHas('success');

        $this->assertDatabaseMissing('posts',[
            'title'=>$post['title'],
        ]);

    }




















} 








