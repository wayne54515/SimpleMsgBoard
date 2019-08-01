<template>
<div>
    <div style="margin-top:5%; display:inline-block; margin-left:2%;">
        <div class="change-page-left2"><a :href="'/article_list'">&lt;-文章列表</a></div>
        <div class="change-page-left2"><a :href="'/'">&lt;-成員列表</a></div>
    </div>
    
    <div class="article-body" v-show="isReady" style="margin-top:20px; width:700px;">
        <h1>{{article.title}}</h1>
        <table class="article-table outer-table" width="70%" align="center">
            <tr class="article-main">
                <td style="padding-left:10px;">
                    <!-- Article -->
                    <div>
                        <p style="float:left;" class="user-name">{{article.user_name}}&nbsp;&nbsp;</p><p style="color:blue;float:left;">{{article.updated_at}}</p>
                        <div v-if="user_info" class="icon">
                            <button id="edit-article-model" v-if="user_info.name == article.user_name" @click="showArticleEdit()">
                                <img src="/img/article/edit.png" alt="編輯文章" title="編輯">
                            </button>
                            <button id="del-article-model" @click="delArticle()">
                                <img src="/img/article/remove.png" alt="刪除文章" title="刪除" border="0">
                            </button>
                        </div>
                        <br><br>
                        <p class="pre-text" v-if="!show_article_edit">{{article.content}}</p>
                    </div>
                </td>
            </tr>
            <tr  v-for="(reply, reply_index) in article.reply" :key="reply.id" class="reply-main" >
                <td style="padding-left:60px; padding-bottom:10px;">
                    <!-- Reply -->
                    <div>
                        <p style="float:left;"  class="user-name">{{reply.user_name}}&nbsp;&nbsp;</p><p style="color:blue;float:left;">{{reply.updated_at}}</p>
                        <div class="icon">
                            <button id="edit-article-model" v-if="user_info.name == reply.user_name" @click="showReplyEdit(reply, reply_index)">
                                <img src="/img/article/edit.png" alt="編輯文章" title="編輯">
                            </button>
                            <button id="del-reply-model" @click="delReply(reply.id, reply_index)" v-if="user_info.name == reply.user_name">
                                <img src="/img/article/remove.png" alt="刪除" title="刪除">
                            </button>
                        </div>
                        <br><br>
                        <p  class="pre-text" v-if="!show_reply_edit[reply_index]">{{reply.content}}</p>
                        <div v-if="show_reply_edit[reply_index]">
                            <textarea v-model="edit_reply.content"></textarea>
                            <br>
                            <button @click="ReplyUpdate(reply.id, reply_index)">更新</button>
                            <button @click="closeReplyEdit(reply_index)">取消</button>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="reply-footer" v-if="user_info">
                <td>
                    <form onsubmit="return false;" style="margin-top:8px;margin-left:10px;">
                        <textarea @blur="setNewReply()" v-model="new_reply.content"></textarea>
                        <button @click="ReplyInsert()">回應</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>

    <transition enter-active-class="show" leave-active-class="hide">
    <!-- 編輯文章 -->
    <div v-if="show_article_edit" class="form-mask">
        <div v-if="showArticleEdit" class="article-model">
            <div class="m-header">
                <h3>編輯貼文</h3>
            </div>

            <div class="m-body">
                <form>
                    <div><p>標題:</p><input type="text" v-model="edit_article.title"></div>
                    <div><p>內容:</p><textarea v-model="edit_article.content"></textarea></div>
                </form>
                <p class="form-alert">{{alert_msg}}</p>
            </div>

            <div class="m-footer">
                <button class="article-insert" @click="ArticleUpdate()">更新推文</button>
                <button class="article-insert" @click="show_article_edit = false">取消</button>
            </div>
        </div>
    </div>
    </transition>

</div>
</template>

<script>
// import modal from "./Modal.vue";

export default {
    props: ['article_id'],

    data: function(){
        return {
            article: {},
            edit_article: {},
            // reply: [],
            new_reply: {},
            edit_reply: {},
            show_reply_edit: [],
            user_info: JSON.parse(window.sessionStorage.getItem('login_user')),
            show_article_edit: false,
            alert_msg: "",

            isReady: false
        }
    },

    methods: {
        init: function(){
            let self = this;
            this.axios.get('/article/' + this.article_id)
                .then(function(response){
                    self.article = response.data.article[0];
                    for(var i=0; i<self.article.reply.length; i++)
                        self.show_reply_edit.push(false);
                    console.log(self.article);
                    self.isReady = true;
                })
                .catch(function(response){
                    console.log(response);
                });
        },

        showArticleEdit: function(){
            this.edit_article = {
                user_name: this.article.user_name,
                title: this.article.title,
                content: this.article.content,
            }
            this.show_article_edit = true;
        },

        ArticleUpdate: function(){
            if((this.edit_article.title != null) & (this.edit_article.content != null)){
                let self = this;

                this.axios({
                    method: 'put',
                    url: '/article/update/'+self.article.id,
                    data: {
                        article: self.edit_article
                    },
                }).then(function(response){
                    self.show_article_edit = false;
                    self.article.content = self.edit_article.content;
                    self.article.title = self.edit_article.title;
                    var ct = new Date();

                    self.article.updated_at = ct.getFullYear() + '-' + self.timeFormat(ct.getMonth()+1) + '-' + self.timeFormat(ct.getDate()) + ' ' 
                                            + self.timeFormat(ct.getHours()) + ":" + self.timeFormat(ct.getMinutes()) + ":" + self.timeFormat(ct.getSeconds());
                    self.edit_article = {};
                    console.log("完成");
                }).catch(function(response){
                    // console.log(self.new_article, self.new_article['title'], self.new_article['content']);
                    console.log(response);
                });
            }
            else{
                this.alert_msg = "有欄位未填";
            }
        },
        
        delArticle: function(){
            let self = this;
            
            //delete slef article
            this.axios({
                method: 'delete',
                url: '/article/del/'+this.article_id,
            }).then(function(response){
                console.log("完成");
                self.delAllReply();
            }).catch(function(response){
                console.log(response);
            });
           
        },

        delAllReply: function(){
            let self = this;

            //delete all reply in self article
            this.axios({
                method: 'delete',
                url: '/reply/delAll/'+this.article_id,
            }).then(function(response){
                console.log("完成");
                window.location.replace('/article_list');
            }).catch(function(response){
                console.log(response);
            });
        },

        ReplyInsert: function(){
            if(this.new_reply.content != null){
                let self = this;

                this.axios.post('/reply', {
                    reply: {
                        user_name: self.user_info.name,
                        content: self.new_reply.content,
                        article_id: self.article_id
                    }
                })
                .then(function(response){
                    self.article.reply.push(response.data.reply);
                    self.show_reply_edit.push(false);
                    new_reply.content = "";
                    console.log("完成");
                })
                .catch(function(response){
                    // console.log(self.new_reply);
                    console.log(response);
                });
            }
            else{
                alert("內容未填寫");
            }
            

        },

        setNewReply: function(){
            // this.new_reply = {
            //     user_name: this.user_info.name,
            //     content: "",
            //     article_id: this.article_id
            // }
        },

        delReply: function(reply_id, reply_index){
            let self = this;
            
            //delete slef reply
            this.axios({
                method: 'delete',
                url: '/reply/del/'+reply_id,
            }).then(function(response){
                self.article.reply.splice(reply_index, 1);
                console.log("完成");
            }).catch(function(response){
                console.log(response);
            });
        },

        ReplyUpdate: function(id, index){
            if(this.edit_reply.content != null){
                let self = this;

                this.axios({
                    method: 'put',
                    url: '/reply/update/'+id,
                    data: {
                        reply: self.edit_reply
                    },
                }).then(function(response){
                    self.$set(self.show_reply_edit, index, false);
                    self.article.reply[index].content = self.edit_reply.content;
                    var ct = new Date(); 
                    self.article.reply[index].updated_at = ct.getFullYear() + '-' + self.timeFormat(ct.getMonth()+1) + '-' + self.timeFormat(ct.getDate()) + ' ' 
                                                        + self.timeFormat(ct.getHours()) + ":" + self.timeFormat(ct.getMinutes()) + ":" + self.timeFormat(ct.getSeconds());
                    self.edit_reply = {};
                    console.log("完成");
                }).catch(function(response){
                    // console.log(self.new_article, self.new_article['title'], self.new_article['content']);
                    console.log(response);
                });
            }
            else{
                this.alert_msg = "有欄位未填";
            }
        },

        showReplyEdit: function(reply_data, index){
            this.edit_reply = {
                user_name: reply_data.user_name,
                content: reply_data.content,
            };
            this.$set(this.show_reply_edit, index, true);
        },

        closeReplyEdit: function(index){
            this.edit_reply = {};
            this.$set(this.show_reply_edit, index, false);
        },

        timeFormat: function(num){
            return parseInt(num)>10 ?num :('0'+num)
        }
    },

    mounted: function(){
        this.init()
    },

    components:{
        
    }
}
</script>

<style>
    .pointer{
        color:blue; 
        margin-left:25px; 
        cursor:pointer;
    }
    button img{
        width: 15px;
        height: 20px;
    }
    
    .article-insert{
        border: 1px black solid;
        border-radius: 3px;
        padding: 15px;
        padding-right: 20px;
        margin-bottom: 20px;
    }

    .article-insert textarea{
        height: 108px;
    }

    .article-insert button{
        background-color:cornflowerblue;
        color:white;
        font-weight: bold;
        width: 10%;
        margin-right: 20px;
        padding: 3px;
    }

    .reply-footer textarea{
        height: 68px;
    }

    .reply-footer button{
        background-color:cornflowerblue;
        color:white;
        font-weight: bold;
        width: 8%;
        float: right;
        margin: 2px;
        padding: 3px;
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

    .reply-footer{
        border-top: 2px blue solid;
    }

    textarea{
        width: 90%;
        resize: none;
    }

    .icon{
        float: right;
        margin-right: 5px;
    }

    .article-table{
        margin-bottom: 35px;
    }

    .article-model {
        display: inline-block;
        width: auto;
        height: auto;
        position: relative;
    }

    .change-page-left2 a{
        text-decoration: none;
        width: 100%;
        position: relative;
        display: inline-block;
        margin-top: 1%; 
        font-size: 17px;
        font-weight: bold;
        text-align: left;
        margin-left: 2%;
        /* background-color: aqua; */
    }
</style>
