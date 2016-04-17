<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArticleTest extends TestCase
{
    public function testCreateArticleWithSignin()
    {
        $roles = [
            ['id' => 1, 'name' => 'reader', 'is_deleted' => 0 ],
            ['id' => 2, 'name' => 'writer', 'is_deleted' => 0 ],
            ['id' => 3, 'name' => 'admin', 'is_deleted' => 0 ],
        ];

        foreach ($roles as $r) {
            factory(App\Models\Role::class)->create($r);
        }

        $permissions = [
            ['id' => 1, 'name' => 'read', 'is_deleted' => 0],
            ['id' => 2, 'name' => 'write', 'is_deleted' => 0],
            ['id' => 3, 'name' => 'role', 'is_deleted' => 0],
            ['id' => 4, 'name' => 'permission', 'is_deleted' => 0],
            ['id' => 5, 'name' => 'user', 'is_deleted' => 0],
        ];

        foreach ($permissions as $p) {
            factory(App\Models\Permission::class)->create($p);
        }

        $permission_roles = [
            ['id' => 1, 'role_id' => 1, 'permission_id' =>1, 'is_deleted' =>0],
            ['id' => 2, 'role_id' => 2, 'permission_id' =>1, 'is_deleted' =>0],
            ['id' => 3, 'role_id' => 2, 'permission_id' =>2, 'is_deleted' =>0],
            ['id' => 4, 'role_id' => 3, 'permission_id' =>1, 'is_deleted' =>0],
            ['id' => 5, 'role_id' => 3, 'permission_id' =>2, 'is_deleted' =>0],
            ['id' => 6, 'role_id' => 3, 'permission_id' =>3, 'is_deleted' =>0],
            ['id' => 7, 'role_id' => 3, 'permission_id' =>4, 'is_deleted' =>0],
            ['id' => 8, 'role_id' => 3, 'permission_id' =>5, 'is_deleted' =>0],
        ];

        foreach ($permission_roles as $p) {
            factory(App\Models\PermissionRole::class)->create($p);
        }

        $user = factory(App\Models\User::class)->create([
            'name' => 'test',
            'email' => 'test@email.com',
            'password' => Hash::make('123456'),
            'role_id' => 2,
        ]);
        $data = ['email'=>'test@email.com', 'password' => '123456'];
        $response = $this->call('POST', 'signin', $data);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $token = $response['token'];


        //test profile
        $header = [
            'HTTP_Authorization' => 'Bearer{' . $token . '}',
        ];
        $data = [
            'title' => 'F1巴林站罗斯夺冠莱科宁亚军 维泰尔爆缸退赛',
            'content' => 'F1巴林站颁奖仪式F1巴林站颁奖仪式
            　　新浪体育讯　　北京时间4月3日，F1巴林站正赛落下帷幕。梅赛德斯车队的罗斯博格发车超过队友一路领跑夺冠，汉密尔顿在一号弯与博塔斯碰撞排名大损后追至第三完赛。法拉利车队莱科宁获亚军，维泰尔热身圈引擎爆缸退赛，职业生涯首次未能参加正赛。
            
            　　周六巴林站的排位赛沿用了90秒淘汰式排位赛，但没有产生任何的惊喜。前四的车手分别是汉密尔顿、罗斯伯格、维泰尔和莱科宁，与揭幕战一模一样。澳大利亚站法拉利发车一举超越奔驰令人记忆犹新。考虑到巴林杆位发车赢得比赛的几率只有50%，无论是罗斯伯格还是法拉利双雄都有机会挑战汉密尔顿。同时第二集团的里卡多、博塔斯、马萨和霍肯伯格也都会抓住机会冲击领奖台。
            
            　　本站阿隆索由于在澳洲站的事故中受伤，未通过FIA医疗检查，确定将缺席正赛。
            
            　　替补其上阵的是迈凯轮的测试车手范多恩。新人小将在排位赛的表现非常不错，以第12击败了冠军队友巴顿，正赛的表现也令人期待。
            
            　　黄昏的巴林萨赫尔气温22度，地表29度，湿度34%，风速很高5.2米/秒。比赛将进行57圈。
            
            　　暖胎圈开始，维泰尔的赛车冒烟了。德国人悲剧了，还没比赛就爆缸，肯定没法比赛了。法拉利损失惨重。
            
            暖胎圈开始，维泰尔的赛车冒烟暖胎圈开始，维泰尔的赛车冒烟
            　　正赛开始，罗斯伯格起得不错，挤掉队友汉密尔顿上到第一。英国人一下在掉到了很后面，似乎有碰撞发生。目前第2至第5是马萨、博塔斯、里卡多和莱科宁。
            
            　　镜头显示汉密尔顿和博塔斯发生了碰撞。芬兰人表示赛车没有问题，但是汉密尔顿的赛车明显有损伤，情况难料。巴林站的开局太劲爆了。
            
            汉密尔顿和博塔斯发生了碰撞汉密尔顿和博塔斯发生了碰撞
            　　第7圈，巴顿的赛车也停在了路旁，看来赛车也有问题。迈凯轮今天只能靠一位新人独自撑场面了。与此同时博塔斯和古铁雷兹已经完成了进站，换上了中性胎。看来的今天大部分车手都会使用两停。
            
            　　第13圈，包括莱科宁在内的大票车手开始陆续完成进站。领头的车手变成了没有进站的罗斯伯格和汉密尔顿。英国人在碰撞中肩翼和侧分流板受损，但依旧跑得很顽强。赛会干事也出示事故调查结果，博塔斯将接受进站的处罚。
            
            　　第14圈，罗斯伯格和汉密尔顿同圈进站，换的分别是软胎和中性胎。出来后德国人还是第一，后面是软胎不断做出最快的莱科宁。两人的差距大约是11秒。里卡多和汉密尔顿在第三和第四。
            
            莱科宁赛中不断做出最快圈速莱科宁赛中不断做出最快圈速
            　　第18圈，汉密尔顿超越里卡多上到第3，已经回到了领奖台。格罗斯让也非常的了不起，守在第5位。哈斯车队本站看样子又要创造历史了。
            
            　　第24圈，格罗斯让继续发功，超越里卡多上到了第四。今天代替阿隆索上阵的信任车手范多恩也非常猛，不知不觉已经杀到了第7。
            
            　　第29圈，汉密尔顿进站换上超软胎，看样子要拼了。30、31圈莱科宁和罗斯伯格也相继进站。前3的名次没有变化。如果罗斯伯格夺冠，算上上赛季，他将实现5连冠。
            
            　　第38圈，莱科宁再次进站换上软胎，看来目的是为了保住第2。罗斯伯格也在第40圈完成最后一停。前三名的名次看来很难再发生变化。
            
            　　比赛进入最后10圈，赛道上变得波澜不惊。但是莱科宁的工程师还在为芬兰人加油：“push，还没完呢，就像去年一样。”目前冰人和罗斯伯格相差了大约6秒。
            
            罗斯伯格首次在巴林站称王。罗斯伯格首次在巴林站称王
            代替阿隆索出战的范多恩带回P10代替阿隆索出战的范多恩带回P10
            　　最终，罗斯伯格发车开始一路带回冠军，首次在巴林站称王。莱科宁遗憾夺得第二。这也是芬兰人11年来第8次在巴林登上领奖台，可惜仍然无缘冠军。汉密尔顿发车遭遇事故仍顽强杀回第三。
            
            　　P4至P10分别是里卡多、格罗斯让、维斯塔潘、科维亚特、马萨、博塔斯和范恩多。罗斯伯格继澳大利亚站取得第6后，本站再次刷新了车队的最好成绩。但更值得点赞的是迈凯伦代替阿隆索出战的范多恩，首场F1比赛便获得积分，实在可喜可贺。
            
            　　2016F1巴林站正赛成绩：
            <img src="http://n.sinaimg.cn/sports/transform/20160404/dzqt-fxqxcnp8508967.jpeg" />
            
            2016F1巴林站正赛成绩2016F1巴林站正赛成绩
            　　（猕猴桃）
            
            文章关键词：F1巴林站罗斯博格汉密尔顿莱科宁',
        ];
        $response = $this->call('POST', 'article', $data, [], [], $header);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertArrayHasKey('article_id', $response);
        $article_id = $response['article_id'];

        $data_new = ['title' => '12'];
        $response = $this->call('PUT', 'article/' . $article_id, $data_new, [], [], $header);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertArrayHasKey('result', $response);
        $this->assertEquals(true, $response['result']);

        $response = $this->call('GET', 'home/article_list/1');
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(1, $response['number']);

        $response = $this->call('GET', 'user/article/1');
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(1, $response['number']);

        $response = $this->call('GET', 'article/' . $article_id);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(1, $response['is_published']);
        $this->assertEquals(0, $response['is_deleted']);
        
        $response = $this->call('GET', 'article/' . $article_id . '/delete');
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(true, $response['result']);

        $response = $this->call('GET', 'article/' . $article_id);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(1, $response['is_published']);
        $this->assertEquals(1, $response['is_deleted']);

        $response = $this->call('GET', 'home/article_list/1');
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(0, $response['number']);
    }
}
