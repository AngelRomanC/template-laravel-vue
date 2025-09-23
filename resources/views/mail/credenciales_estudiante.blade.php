@component('mail::message')

# Credenciales de Acceso
Bienvenido a nuestra comunidad Upemor


Se ha creado una cuenta para ti.

**Correo:** {{ $email }}  
**Contraseña:** {{ $password }}

[Iniciar sesión]({{ url('/login') }})

{{-- Image --}}
<img src="{{ $message->embed(public_path().'/storage/imagenes/Upemor.jpg') }}" alt="Imagen">
<img src="{{ url('/storage/imagenes/Upemor.jpg') }}" alt="Imagen">



Por favor, cambia tu contraseña después de iniciar sesión.



@endcomponent