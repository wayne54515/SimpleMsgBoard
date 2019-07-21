
<template>
<div class="content">
    <div class="nav-bar">
        <div v-if="page_state == 'has_login'">
            <span id="welcome">welcome {{user.name}}</span>
            <span class="button" onclick="logout()">LOGOUT</span>
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
                    <td><span class="button" @click="showProfile(userIndex)">{{user.name}}</span></td>
                    <td>{{user.avatar}}</td>
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
                </div>
                <div class="profile-avatar">
                    <img>
                    <input type="file" value="Choose File">
                    <center><button type="submit" @click="uploadAvatar()">New Avatar</button></center>
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
            user_state: "guest",
            page_state: "not_login",
            form_state: "",
            profile_state: false,
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
                    if(response.data.status.status)
                        this.user_info = user_list[0];
                    else
                        this.alert_msg = response.data.status.error;
                })
                .catch(function(response){
                    console.log(response);
                })
        },

        clearFormData: function(){
            this.user = {};
            this.alert_msg = "";
        },

        checkLoginForm: function(){
            if((this.user.email) & (this.user.password))
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

        showProfile: function(index){
            this.profile = this.user_list[index];
            this.profile_state = true;
        },

        uploadAvatar: function(){

        }

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

    .user-table td{
        text-align: center;
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

    .profile-content {
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