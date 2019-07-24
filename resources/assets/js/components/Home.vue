
import { setTimeout } from 'timers';
<template>
<div class="content">
    <div class="nav-bar">
        <div v-if="page_state == 'has_login'">
            <span id="welcome">WELCOME {{user_info.name}}</span>
            <span class="button" @click="logout()">LOGOUT</span>
        </div>
        <div v-if="page_state == 'not_login'">
            <span class="button" @click="showLoginPage()">LOGIN</span>
            <span class="button" @click="showRegisterPage()">REGISTER</span>
        </div>
    </div>
    <transition enter-active-class="show" leave-active-class="hide">
    <div class="form-mask" v-if="(form_state == 'login') | (form_state == 'register')">
        <div class="form">
            <table>
                <thead>
                    <th colspan="2">
                        <span class="login" v-if="form_state == 'login'">LOGIN</span>
                        <span class="register" v-if="form_state == 'register'">REGISTER</span>
                        <span class="close-page" @click="closeFormPage()">X</span>
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <form class="login input-box show" v-if="form_state == 'login'" name="login-form" onsubmit="return false;">
                                <input type="email" name="email" placeholder="Email Address" v-model="user.email">
                                <input type="password" name="password" placeholder="Password" v-model="user.password">
                                <button type="submit" @click="checkLoginForm()">Login</button>
                            </form>
                            <form class="register input-box" v-if="form_state == 'register'" name="register-form" onsubmit="return false;">
                                <input type="text" name="name" placeholder="Username" v-model="user.name">
                                <input type="email" name="email" placeholder="Email Address" v-model="user.email">
                                <input type="password" name="password" placeholder="Password" v-model="user.password">
                                <input type="password" name="confirm" placeholder="Confirm Password" v-model="user.confirm">
                                <label>Favorite Color :</label>
                                <input type="color" name="color" placeholder="pick a color" v-model="user.color"><br>
                                <label>Sex :</label>
                                <input type="radio" name="sex" value="male" group="1" v-model="user.sex">Male
                                <input type="radio" name="sex" value="female" group="1" v-model="user.sex">Female
                                <input type="radio" name="sex" value="other" group="1" v-model="user.sex">Other
                                <button type="submit" @click="checkRegisterForm()">Register</button>
                            </form>
                            <p id="form-alert">{{alert_msg}}</p>  
                        </td>
                    </tr>
                    <tr class="form-bottom">
                        <td class="login-page" @click="showLoginPage()">LOGIN</td>
                        <td class="register-page" @click="showRegisterPage()">REGISTER</td>
                    </tr>
                </tbody>
            </table> 
        </div>
    </div>
    </transition>
        
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
                <span class="close-page" @click="profile_state = false;">X</span>
                <p class="seperate-line"></p>
                <div class="profile-info">
                    <span><p>Name:{{profile.name}}</p></span>
                    <span><p>Email:{{profile.email}}</p></span>
                    <span><p>Color:{{profile.color}}</p></span>
                    <span><p>Sex:{{profile.sex}}</p></span>
                    <span><p>Create Time:{{profile.created_at}}</p></span>
                    <button @click="showUploadPage()" v-if="user_info.name == profile.name">Upload File</button>
                </div>
                <div class="profile-avatar">
                    <img :src="profile.avatar.url" v-if="profile.avatar != null"/>
                    <form v-if="user_info.name == profile.name" onsubmit="return false;">
                        <input type="file" id="image" ref="image" v-on:change="handleImageUpload()"/>
                        <center><button type="submit" @click="uploadAvatar()">New Avatar</button></center>
                    </form>
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
                                <td>編輯</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="file in profile.file" :key="file.id" v-bind="profie.file">
                                <td>{{file.id}}</td>
                                <td><a :href="file.download_link">{{file.file_name}}</a></td>
                                <td>{{file.file_size}}</td>
                                <td>{{file.file_type}}</td>
                                <td>{{file.updated_at}}</td>
                                <td><span class="button" @click="rename(file.download)">Edit</span></td>
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
            user_info: {},//login user data
            profile: {},//user profile data
            avatar_file: "",
            file:"",
            user_index: 0,
            file_info: {},
            user_state: "guest",
            page_state: "not_login",
            form_state: "",
            profile_state: false,
            file_state: false,
            alert_msg: "",// login/register錯誤警告
        }
    },

    methods: {
        init: function(){
            this.getUserList();
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

        showLoginPage: function(){
            this.form_state = 'login';
            this.clearFormData();
        },

        showRegisterPage: function(){
            this.form_state = 'register';
            this.clearFormData();
            this.user.color = "#000000";
        },

        closeFormPage: function(){
            this.form_state = '';
            this.clearFormData();
        },

        insertUser: function(){
            let self = this;
            console.log(self.user);
            this.axios.post('/user', {
                    user: self.user
                })
                .then((response) => {
                    console.log(response);
                    self.getUserList();
                    self.closeFormPage();
                })
                .catch((response) => {
                    console.log(response);
                })
        },

        login: function(){
            let self = this;
            this.axios.post('/user/account', {
                    user: self.user
                })
                .then(function(response){
                    console.log(response);
                    // console.log(response.data.status['status']);
                    // console.log(response.data.status['error']);
                    if(response.data.status['status']){
                        self.page_state = "has_login";
                        self.user_info = response.data.status['user'];
                        self.closeFormPage();
                    }    
                    else
                        self.alert_msg = response.data.status['error'];
                })
                .catch(function(response){
                    console.log(response);
                })
        },

        logout: function(){
            this.page_state = "not_login";
            this.user_info = {};
        },

        clearFormData: function(){
            this.user = {};
            this.alert_msg = "";
        },

        checkLoginForm: function(){
            if((this.user.email != null) & (this.user.password != null))
                this.login();
            else
                this.alert_msg = "有欄位沒填寫";
        },

        checkRegisterForm: function(){
            if((this.user.name != null) & (this.user.email != null) & (this.user.password != null) & (this.user.confirm != null)  & (this.user.sex != null))
                if(this.user.password == this.user.confirm)
                    this.checkNameExist();
                else   
                    this.alert_msg = "密碼與認證密碼不同";
            else
                this.alert_msg = "有欄位沒填寫";
        },

        checkEmailExist: function(){
            let self = this;
            this.axios.get('/user/check_email/' + this.user.email)
                .then(function(response){
                    console.log(response);
                    if(response.data.status)
                        self.alert_msg = "信箱已被註冊";
                    else{
                        self.insertUser();
                    }
                })
                .catch(function(response){
                    console.log(response);
                })
        },

        checkNameExist: function(){
            let self = this;
            // console.log("name check"+this.user.name);
            this.axios.get('/user/check_name/' + this.user.name)
                .then(function(response){
                    if(response.data.status)
                        self.alert_msg = "名字已被使用";
                    else
                        self.checkEmailExist();
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
                                // self.file_state = false;
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

        rename($pre_url){

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
        display: inline-block;
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
        margin-top: 7%;
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
        width: 46%;
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
</style>