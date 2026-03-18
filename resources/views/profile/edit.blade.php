<x-app-layout :pageTitle="'Ajustes de Perfil'">
    <div class="page-header">
        <div class="page-header-left">
            <h1>Ajustes de Perfil</h1>
            <p>Gestiona tu información personal y configuración de seguridad</p>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:24px; max-width:800px">
        <div class="card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card" style="border-color:var(--color-danger)">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
