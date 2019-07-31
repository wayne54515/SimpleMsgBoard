<template>
<div>
    <div class="change-page-left"><a :href="'/'">&lt;-文章列表</a></div>

    <div class="article-body" v-show="isReady" style="margin-top:20px; width:700px;">
        <p style="text-align:left; font-size:20px;">{{article_number}}則留言</p>
        <table class="article-table outer-table" v-for="(article, article_index) in article" :key="article.id" width="70%" align="center">
            <tr class="article-main">
                <td style="padding-left:10px;">
                    <!-- Article -->
                    <div>
                        <p style="float:left;" class="user-name">{{article.user.name}}&nbsp;&nbsp;</p><p style="color:blue;float:left;">{{article.updated_at}}</p>
                        <div v-if="user[0] == article.user.name || user[1] =='admin'" class="icon">
                            <button id="edit-article-model" v-if="user[0] == article.user.name" @click="showArticleEdit(article.id, article_index)">
                                <img src="/img/article/edit.png" alt="編輯文章" title="編輯">
                            </button>
                            <button id="del-article-model" @click="delArticle(article.id, article_index)">
                                <img src="/img/article/remove.png" alt="刪除文章" title="刪除" border="0">
                            </button>
                        </div>
                        <br><br>
                        <p class="pre-text">{{article.content}}</p>
                        
                        <p @mousedown="showReplyInsertModel(article_index)" style="color:blue; cursor:pointer;">回覆</p>
                        <p v-if="(showReplyTable[article_index] && article.reply.length > 0) || (article.reply.length > 0 && article.reply.length < 3 && (article.reply.length - show_reply_initial[article_index]) < 1)" 
                            @mousedown="hideReply(article_index)" class="pointer">隱藏{{article.reply.length}}則留言</p>
                        <p v-if="!showReplyTable[article_index] && (article.reply.length - show_reply_initial[article_index]) > 0" 
                            @mousedown="showReply(article_index)" class="pointer">顯示{{article.reply.length - show_reply_initial[article_index]}}則留言</p>
                    </div>
                </td>
            </tr>
            <tr  v-for="(reply, reply_index) in article.reply" :key="reply.id" class="reply-main" >
                <td style="padding-left:60px; padding-bottom:10px;" v-if="showReplyTable[article_index] || (reply_index - article.reply.length + show_reply_initial[article_index]) >= 0">
                    <!-- Reply -->
                    <div>
                        <p style="float:left;"  class="user-name">{{reply.user.name}}&nbsp;&nbsp;</p><p style="color:blue;float:left;">{{article.updated_at}}</p>
                        <button id="del-reply-model" @click="delReply(reply.id, article_index, reply_index)" 
                                v-if="user[0] == article.user.name || user[1] =='admin'" style="float:right;">
                            <img src="/img/article/remove.png" alt="刪除" title="刪除">
                        </button>
                        <br><br>
                        <p  class="pre-text">{{reply.content}}</p>
                    </div>
                </td>
            </tr>
            <tr class="reply-footer" v-if="showReplyInsert[article_index]">
                <td>
                    <form onsubmit="return false;" style="margin-top:8px;margin-left:10px;">
                        <textarea @blur="setNewReply(user[0], article.id, new_reply_list[article_index])" v-model="new_reply_list[article_index]"></textarea>
                        <button @click="ReplyInsert(article_index)">回應</button>
                    </form>
                </td>
            </tr>
        </table>
        <div  class="article-insert">
            <form onsubmit="return false;" style="margin-top:35px;margin-left:10px;">
                <textarea  @blur="setNewArticle(user[0], cardId, article_content)" v-model="article_content"></textarea>
                <div align="right">
                    <button @click="ArticleInsert()" v-if="user[1] != 'guest'">發怖</button>
                    <button @click="Alert()" v-if="user[1] == 'guest'">發怖</button>
                </div>
            </form>
            <p align="left" style="font-weight:bold;">發表留言的身分:&nbsp;{{user[0]}}</p>
        </div>

    </div>

    <!-- 編輯文章 -->
    <modal v-if="showArticleModal" @close="showArticleModal = false" class="atricle-model">
        <div slot="header">
            <h3 v-if="articleModalState == 'edit'">編輯文章</h3>
        </div>

        <div slot="body">
            <form>
                <div class="row">{{user[0]}}</div>
                <div class="row"><label>內容</label><textarea v-model="new_article.content"></textarea></div>
            </form>
        </div>

        <div slot="footer">
            <button class="modal-default-button" v-if="articleModalState == 'edit'" @click="ArticleUpdate()">更新</button>
            <button class="modal-default-button" @click="showArticleModal = false">取消</button>
        </div>
    </modal>
</div>
</template>

<script>
// import modal from "./Modal.vue";

export default {
    props: ['user'],

    data: function(){
        return {
            article: [],
            reply: [],
            articleModalState: "",
            showArticleModal: false,
            new_reply_list: [],
            article_content: "",
            showReplyTable: [],
            showReplyInsert: [],
            article_number: 0,
            show_reply_initial: [],


            isReady: false
        }
    },

    methods: {
        init: function(){
            console.log(this.cardId);
            console.log(this.user);
            let self = this;
            this.axios.get('/article/' + this.cardId)
                .then(function(response){
                    self.article = response.data.article;
                    console.log(self.article);
                    self.article_number = response.data.article_number;
                    for(var i = 0; i < self.article_number; i++){
                        self.showReplyInsert.push(false);
                        self.showReplyTable.push(false);
                        self.show_reply_initial.push(2);
                    }
                    self.isReady = true;
                })
                .catch(function(response){
                    console.log(response);
                });
        },

        Alert: function(){
            alert("尚未登入");
            this.article_content = null;
        },

        showReplyInsertModel: function(index){
            if(this.user[1] == 'guest'){
                alert("尚未登入");
            }
            else
                this.$set(this.showReplyInsert, index, true);
        },

        showReply: function(index){
            this.$set(this.showReplyTable, index, true);
        },

        hideReply: function(index){
            this.$set(this.showReplyTable, index, false);
            this.$set(this.show_reply_initial, index, 0);
        },

        showArticleEdit: function(article_id, index){
            let self = this;
            this.articleIndex = index
            this.setNewArticle(this.user[0], this.cardId);

            this.axios.get('/article/edit/'+article_id)
                .then(function(response){
                    self.new_article = response.data.article;
                    self.articleModalState = "edit";
                    self.showArticleModal = true;
                })

        },

        ArticleUpdate: function(){
            let self = this;

            this.axios({
                method: 'put',
                url: '/article/update/'+self.new_article.id,
                data: {
                    article: self.new_article
                },
            }).then(function(response){
                self.showArticleModal = false;
                self.article[self.articleIndex]['title'] = self.new_article['title'];
                self.article[self.articleIndex]['content'] = self.new_article['content'];
                // console.log(self.new_article, self.new_article['title'], self.new_article['content'], self.article[self.articleIndex]);
                console.log("完成");
            }).catch(function(response){
                // console.log(self.new_article, self.new_article['title'], self.new_article['content']);
                console.log(response);
            });
        },

        ArticleInsert: function(){
            let self = this;

            this.axios.post('/article', {
                article: self.new_article
            })
            .then(function(response){
                self.showArticleModal = false;
                // console.log(response.data.article);
                self.article.push(response.data.article);
                self.article_number+=1;
                
                self.article_content = null;
                self.showReplyInsert.push(false);
                self.showReplyTable.push(false);
                self.show_reply_initial.push(2);
                // console.log(self.article);
                console.log("完成");
                
            })
            .catch(function(response){
                // console.log(self.new_article);
                // console.log(response.data.article);
                console.log(response);
            });
        },
        
        delArticle: function(article_id, index){
            let self = this;
            
            //delete slef article
            this.axios({
                method: 'delete',
                url: '/article/del/'+article_id,
            }).then(function(response){
                self.article.splice(index, 1);
                self.showReplyInsert.splice(index, 1);
                self.showReplyTable.splice(index, 1);
                self.show_reply_initial.splice(index, 1);
                self.article_number-=1;
                console.log("完成");
            }).catch(function(response){
                console.log(response);
            });
            //delete all reply in self article
            this.axios({
                method: 'delete',
                url: '/reply/delAll/'+article_id,
            }).then(function(response){
                self.article[index].reply = null;
                console.log("完成");
            }).catch(function(response){
                console.log(response);
            });
        },

        setNewArticle: function(user_name , card_id, content){
            this.new_article = {
                user_name: user_name,
                title: "",
                content: content,
                card_id: card_id
            }
        },

        ReplyInsert: function(index){
            let self = this;
            self.articleIndex = index

            this.axios.post('/reply', {
                reply: self.new_reply
            })
            .then(function(response){
                self.showArticleModal = false;
                self.article[self.articleIndex].reply.push(response.data.reply);
                self.new_reply_list[self.articleIndex] = null;
                // self.showReplyTable[self.articleIndex] = true;
                // console.log(self.new_reply, self.article[self.articleIndex].reply);
                console.log("完成");
            })
            .catch(function(response){
                // console.log(self.new_reply);
                console.log(response);
            });

        },

        setNewReply: function(user_name, article_id, content){
            this.new_reply = {
                user_name: user_name,
                content: content,
                article_id: article_id
            }
        },

        delReply: function(reply_id, article_index, reply_index){
            let self = this;
            
            //delete slef reply
            this.axios({
                method: 'delete',
                url: '/reply/del/'+reply_id,
            }).then(function(response){
                self.article[article_index].reply.splice(reply_index, 1);
                console.log("完成");
            }).catch(function(response){
                console.log(response);
            });
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

    .change-page-left a{
        text-decoration: none;
        width: 100%;
        position: relative;
        display: inline-block;
        margin-top: 5%; 
        font-size: 17px;
        font-weight: bold;
        text-align: left;
    }
</style>
