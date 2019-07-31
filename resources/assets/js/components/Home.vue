

<template>
<div class="content">    
    <div class="change-page-right"><a :href="'/article_list'">文章列表-></a></div>

    <div class="user">
        <table>
            <thead>
                <th>名字</th>
                <th>頭像</th>
                <th>信箱</th>
                <th>性別</th>
                <th>創建時間</th>
            </thead>
            <tbody class="user-table">
                <tr v-for="(user, userIndex) in user_list" :key="user.id" v-bind:style="{color: user.color}">
                    <td><span class="button" @click="setUserIndex(userIndex);showProfile()">{{user.name}}</span></td>
                    <td><img :src="user.avatar.url" v-if="user.avatar != null"/></td>
                    <td>{{user.email}}</td>
                    <td>{{user.sex}}</td>
                    <td>{{user.created_at}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="profile">
        <transition enter-active-class="show" leave-active-class="hide">
        <div class="form-mask" v-if="profile_state">
            <div class="profile-content"  v-bind:style="{color: profile.color}">
                <h3 style="display: inline">MEMBER INFO</h3>
                <span class="close-page" @click="profile_state = false; file_edit = {}">X</span>
                <p class="seperate-line"></p>
                <div class="profile-info">
                    <span><p>Name:{{profile.name}}</p></span>
                    <span><p>Email:{{profile.email}}</p></span>
                    <span><p>Color:{{profile.color}}</p></span>
                    <span><p>Sex:{{profile.sex}}</p></span>
                    <span><p>Create Time:{{profile.created_at}}</p></span>
                    <div v-if="user_info">
                        <button @click="showUploadPage()" v-if="user_info.name == profile.name">Upload File</button>
                    </div>
                </div>
                <div class="profile-avatar">
                    <img :src="profile.avatar.url" v-if="profile.avatar != null"/>
                    <div v-if="user_info">
                        <form v-if="user_info.name == profile.name" onsubmit="return false;">
                            <input type="file" id="image" ref="image" v-on:change="handleImageUpload()"/>
                            <center><button type="submit" @click="uploadAvatar()">New Avatar</button></center>
                        </form>
                    </div>
                </div>
                <div class="upload-file" v-if="profile.file[0] != null">
                    <table>
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>檔名</td>
                                <td>大小</td>
                                <td>種類</td>
                                <td>時間</td>
                                <td v-if="user_info">
                                    <div v-if="user_info.name == profile.name">編輯</div>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p_file, p_file_index) in profile.file" :key="p_file.id">
                                <td>{{p_file.id}}</td>
                                <td>
                                    <a :href="p_file.download_link" download v-if="!file_edit[p_file_index]"  v-bind:style="{color: profile.color}">
                                        {{p_file.file_name}}
                                    </a>
                                    <input type="text" v-if="file_edit[p_file_index]" v-model="new_file_name">
                                </td>
                                <td>{{p_file.file_size}}</td>
                                <td>{{p_file.file_type}}</td>
                                <td>{{p_file.updated_at}}</td>
                                <td v-if="user_info">
                                    <div v-if="user_info.name == profile.name">
                                        <span class="button" @click="showRename(p_file_index)" v-if="!file_edit[p_file_index]">Edit</span>
                                        <button @click="rename(p_file_index)" v-if="file_edit[p_file_index]">Rename</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </transition>

        <transition enter-active-class="show" leave-active-class="hide">
        <div class="form-mask" v-if="file_state">
            <div class="file-content">
                <h3 style="display: inline">UPLOAD FILE</h3>
                <span class="close-page" @click="file_state = false;">X</span>
                <p class="seperate-line"></p>
                <div class="file-info">
                    <span><p>Name : {{user_info.name}}</p></span>
                    <span><p>File Name : {{file.name}}</p></span>
                    <span><p>File Size : {{file.size}}</p></span>
                    <span><p>File Type : {{file.type}}</p></span>
                    <span><p>Create Time : {{file.lastModifiedDate}}</p></span>
                </div>
                <div class="file-form">
                    <form onsubmit="return false;">
                        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
                        <button type="submit" @click="uploadFile()">Upload File</button>
                    </form>
                </div>
            </div>
        </div>
        </transition>
        
    </div>
</div>
</template>

<script>

export default {

    data: function() {
        return {
            user_list: {},//會員清單
            user: {},//form user data
            user_info: JSON.parse(window.sessionStorage.getItem('login_user')),//login user data
            profile: {},//user profile data
            avatar_file: "",
            file:"",
            user_index: 0,
            file_info: {},
            user_state: "guest",
            new_file_name: "",
            profile_state: false,
            file_state: false,
            file_edit: {},
            alert_msg: "",// login/register錯誤警告
        }
    },

    methods: {
        init: function(){
            this.getUserList();
            // this.user_info = JSON.parse(window.sessionStorage.getItem('login_user'));
        },

        getUserList: function(){
            let self = this;
            this.axios.get('/user')
                .then(function(response){
                    self.user_list = response.data.users;
                    self.profile = self.user_list[self.user_index];
                })
                .catch(function(response){
                    console.log(response);
                })
        },

        setUserIndex: function(index){
            this.user_index = index;
        },

        showProfile: function(){
            this.profile = this.user_list[this.user_index];
            this.profile_state = true;
        },

        showUploadPage: function(index){
            this.file_state = true;
        },

        uploadAvatar: function(){
            if(this.avatar_file == null)
                alert("未選擇圖檔");
            else{
                if(this.avatar_file.size <= 5*1024*1024){
                    let self = this;
                    let formData = new FormData();
                    formData.append('image', this.avatar_file);
                    formData.append('user_name', this.user_info.name);
                    formData.append('img_name', this.avatar_file.name);
                    formData.append('img_size', this.avatar_file.size);
                    formData.append('img_type', this.avatar_file.type);
                    if(self.profile.avatar != null)
                        formData.append('pre_url', self.profile.avatar.url);
                    else
                        formData.append('pre_url', "");

                    self.axios.post( '/file/avatar/',
                        formData,{
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.status){
                                // self.profile_state = false;
                                self.getUserList();
                            }
                            else
                                alert("檔名已存在");

                        })
                        .catch(function(response){
                            console.log(response);
                        });
                }
                else
                    alert("圖檔超過5MB");
                // this.profile = this.user_list[this.user_index];
            }
            
        },

        handleImageUpload(){
            console.log(this.$refs.image.files[0]);
            this.avatar_file = this.$refs.image.files[0];
        },

        uploadFile: function(){
            if(this.file == null)
                alert("未選擇檔案");
            else{
                if(this.file.size <= 5*1024*1024){
                    let self = this;
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('user_name', this.user_info.name);
                    formData.append('file_name', this.file.name);
                    formData.append('file_size', this.file.size);
                    formData.append('file_type', this.file.type);

                    self.axios.post( '/file/',
                        formData,{
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function(response){
                            console.log(response.data);
                            if(response.data.status){
                                self.file_state = false;
                                self.getUserList();
                            }
                            else
                                alert("檔名已存在");

                        })
                        .catch(function(response){
                            console.log(response);
                        });
                }
                else
                    alert("檔案超過5MB");
                // this.profile = this.user_list[this.user_index];
            }
            
        },

        handleFileUpload(){
            console.log(this.$refs.file.files[0]);
            this.file = this.$refs.file.files[0];
        },

        showRename: function(index){
            this.$set(this.file_edit, index, true);
            this.new_file_name = this.profile.file[index].file_name;
        },

        rename: function(index){
            let self = this;

            this.axios({
                method: 'put',
                url: '/file/rename/',
                data:{
                    old_data: self.profile.file[index],
                    new_name: self.new_file_name,
                }
                }).then(function(response){
                    console.log(response);
                self.getUserList();
                }).catch(function(response){
                    console.log(response);
                })
            // console.log("rename function"+index);
            this.file_edit[index] = false;
        },

    },

    mounted: function(){
        this.init();
    },
    
    components: {
        
    }
}
        
</script>

<style>
    body{
        padding: 0px;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        /* table-layout: fixed;  */
    }

    a {
        text-decoration:none;
        color:black;
    }

    .nav-bar{
        display: block;
        width: 100%;
    }
        
    .button{
        cursor: pointer;
    }

    .nav-bar div{
        margin: 1%;
        position: fixed;
        right: 3%;
        width: auto;
        height: inherit;
        font-size: 20px;
    }
      
    td, th {
        border: 1px solid #dddddd;
        padding: 10px;  
    }

    .form-bottom{
        text-align: center;
    }

    .form-bottom td{
        cursor: pointer;
    }
        
    .form {
        width: 30%;
        margin-left: 35%;
        margin-top: 10%;
        text-align: left;
        position: absolute;
        overflow: hidden;
        background-color: white;
    }

    .form-mask{
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.747);
        transition: opacity .3s ease;
    }

    .form th{
        padding-left: 6%;
    }

    .input-box input[type="text"],.input-box input[type="password"],.input-box input[type="email"]{
        display: block;
        margin: 5%;
        width: 90%;
        font-size: 17px;
    }

    .input-box label{
        margin-top: 2%;
        margin-left: 5%;
        margin-bottom: 2%;
        margin-right: 2%;
        text-align: left;
        display: inline-block;
    }

    .input-box button{
        margin-top: 4%;
        width: 30%;
        margin-left: 35%;
        margin-bottom: 4%;
        padding: 2%;
        display: block;
    }

    .close-page{
        float: right;
        overflow: hidden;
        cursor: pointer;
    }

    .user{
        width: 50%;
        margin-left: 25%;
        margin-top: 3%;
    }

    .user-table td, .user th{
        text-align: center;
    }

    .user-table img{
        widows: 40px;
        height: 40px;
    }

    #form-alert{
        text-align: center;
    }

    .show {
        animation: go 1s;
    }

    .hide {
        animation: back 1s;
    }

    @keyframes go {
        from { opacity: 0; }
        to {opacity: 1;}
    }

    @keyframes back {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    .profile-content, .file-content {
        min-width: 46%;
        width: auto;
        margin-left: 27%;
        margin-top: 10%;
        text-align: left;
        position: absolute;
        overflow: hidden;
        background-color: white;
        padding: 2%;
    }

    .profile-info {
        display: inline-block;
        /* background-color: chartreuse; */
        width: 50%;
    }
    .profile-info p{
        font-size: 15px;
        margin: 2%;
    }

    .profile-avatar{
        float: right;
        overflow: hidden;
        width: 45%;
        /* background-color: coral; */
    }
    
    .profile-avatar img{
        width: 160px;
        height: 160px;
        display: block;
        margin: auto;
        margin-bottom: 5px;
    }

    .profile-avatar button{
        padding:2%;
        background-color: rgb(26, 167, 167);
        font-weight: bold;
        font-size: 15px;
        color:#dddddd;
        border-radius: 5px;
        text-align: center;
        display : block;
    }

    .seperate-line{
        display: block;
        width: auto;
        margin-bottom: 5%;
    }

    .upload-file a {
        text-decoration:none;
    }

    .change-page-right a{
        text-decoration: none;
        width: 100%;
        position: relative;
        display: inline-block;
        margin-top: 5%; 
        font-size: 17px;
        font-weight: bold;
        text-align: right;
    }
</style>