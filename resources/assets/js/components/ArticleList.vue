<template>
<div class="article-content">
    <div class="change-page-left"><a :href="'/'">&lt;-成員列表</a></div>

    <div class="write-button" v-if="user_info"><button @click="showArticleModal = true;" class="">新增貼文</button></div>

    <div class="article-body" style="margin-top:20px; width:700px;">
        <table class="article-table outer-table" align="center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Poster</th>
                    <th>Reply Number</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody v-for="(article) in article" :key="article.id">
                <tr class="article-main">
                    <td>{{article.id}}</td>
                    <td><a :href="'/article_list/'+article.id">{{article.title}}</a></td>
                    <td>{{article.user_name}}</td>
                    <td>{{article.reply.length}}</td>
                    <td>{{article.updated_at}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <transition enter-active-class="show" leave-active-class="hide">
    <!-- 編輯文章 -->
    <div v-if="showArticleModal" class="form-mask">
        <div v-if="showArticleModal" class="article-model">
            <div class="m-header">
                <h3>新增貼文</h3>
            </div>

            <div class="m-body">
                <form>
                    <div><p>標題:</p><input type="text" v-model="article_content.title"></div>
                    <div><p>內容:</p><textarea v-model="article_content.content"></textarea></div>
                </form>
                <p class="form-alert">{{alert_msg}}</p>
            </div>

            <div class="m-footer">
                <button class="article-insert" @click="ArticleInsert()">推文</button>
                <button class="article-insert" @click="showArticleModal = false">取消</button>
            </div>
        </div>
    </div>
    </transition>
</div>
</template>

<script>

export default {
    props: [],

    data: function(){
        return {
            article: {},
            articleModalState: "",
            showArticleModal: false,
            article_content: {},
            alert_msg: "",
            user_info: JSON.parse(window.sessionStorage.getItem('login_user')),
        }
    },

    methods: {
        init: function(){

            let self = this;
            this.axios.get('/article')
                .then(function(response){
                    self.article = response.data.article;
                    console.log(self.article);
                })
                .catch(function(response){
                    console.log(response);
                });
        },     

        ArticleInsert: function(){

            if((this.article_content.title != null) & (this.article_content.content != null)){
                let self = this;
                self.article_content['user_name'] = self.user_info.name;

                this.axios.post('/article', {
                    article: self.article_content
                })
                .then(function(response){
                    self.showArticleModal = false;
                    // console.log(response.data.article);
                    self.article.push(response.data.article);
                    
                    self.article_content = null;
                    // console.log(self.article);
                    console.log("完成");
                    
                })
                .catch(function(response){
                    console.log(response);
                });
            }
            else{
                this.alert_msg = "有欄位未填寫";
            }
            
        },

    },

    mounted: function(){
        this.init()
    },

    components:{
        
    }
}
</script>

<style>
    .form-alert{
        text-align: center;
    }

    .pointer{
        color:blue; 
        margin-left:25px; 
        cursor:pointer;
    }

    .article-body{
        width: 50%;
        margin-left: 25%;
    }
    
    .article-insert{
        border: 1px snow solid;
        border-radius: 3px;
        padding: 10px;
        margin-bottom: 20px;
        color: black
    }

    .article-table th{
        text-align: center;
    }

    .user-name{
        font-weight: bold;
        font-size: 15px;
    }

    .pre-text{
        white-space: pre-line;
        word-wrap: break-word;
        width: 100%;
        word-break: keep-all;
    }

    textarea{
        width: 90%;
        resize: none;
        min-height: 150px;
    }

    .icon{
        float: right;
        margin-right: 5px;
    }

    .article-table{
        margin-bottom: 35px;
    }

    .article-model {
        width: 50%;
        margin-left: 25%;
        margin-top: 10%;
        text-align: left;
        position: absolute;
        overflow: hidden;
        background-color: white;
        display: block;
        padding: 3%;
    }

    .article-model p{
        margin-top: 2%;
    }
    
    .poster{
        font-size: 17px;
    }

    .m-body{
        margin-top: 5%;
        margin-bottom: 5%;
    }

    .m-body input, .m-body textarea{
        width: 100%;
    }

    .change-page-left a{
        text-decoration: none;
        width: 100%;
        position: relative;
        display: inline-block;
        margin-top: 5%; 
        font-size: 17px;
        font-weight: bold;
        text-align: left;
        margin-left: 3%;
    }

    .write-button{
        border: 1px snow solid;
        border-radius: 3px;
        font-size: 17px;
        font-weight: bold;
        float: right;
        position: relative;
        margin-right: 3%;
    }

</style>
