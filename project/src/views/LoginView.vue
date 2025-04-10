<script setup lang="ts">

</script>

<template>
    <div class="auth-container login-container full-height">
        <h1 class="title">Login to CircuitForge</h1>
        <div class="form-section">
            <div class="input input-login">
                <label for="login">username or email</label>
                <input type="text" name="login" id="login">
            </div>

            <div class="input input-password">
                <label for="password">password</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me">
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <button class="signup">Login</button>

        <div class="footnote">
            <span class="footnote-text">Don't have an Account? </span>
            <RouterLink to="/register" class="footnote-link">Sign Up</RouterLink>
        </div>

    </div>
</template>

<style scoped>

</style>
