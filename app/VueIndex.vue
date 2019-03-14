<template>
    <div>
        <nav id="w0" class="navbar">
            <div class="container">
                <div class="navbar-header">
                    <router-link to="/" class="navbar-brand">My Application</router-link>
                </div>

                <div id="w0-collapse" class="collapse navbar-collapse">
                    <ul id="w1" class="navbar-nav navbar-right nav">
                        <li >
                            <router-link to="/">Home</router-link>
                        </li>
                        <li >
                            <router-link to="/about">About</router-link>
                        </li>
                        <li >
                            <router-link to="/login">Login</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: 'vue-index',
    }
</script>
