@component('mail::message')
# Introduction

Mensagem do corpo.

@component('mail::button', ['url' => ''])
Botao
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
