<script setup lang="ts">

</script>

<template>
    <div class="auth-container register-container full-height">
        <h1 class="title">Register on CircuitForge</h1>
        <div class="form-section">
            <div class="input input-username">
                <label for="username">username</label>
                <input type="text" name="username" id="username">
            </div>

            <div class="input input-email">
                <label for="email">email</label>
                <input type="email" name="email" id="email">
            </div>

            <div class="input input-password">
                <label for="password">password</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="input input-confirm-password">
                <label for="confirm-password">confirm password</label>
                <input type="password" name="confirm-password" id="confirm-password">
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me">
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <button class="signup">Sign Up</button>

        <div class="footnote">
            <span class="footnote-text">Already have an Account? </span>
            <RouterLink to="/login" class="footnote-link">Login</RouterLink>
        </div>

    </div>
</template>

<style scoped>


</style>
