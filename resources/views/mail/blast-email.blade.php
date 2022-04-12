@extends('layouts.mail')

@section('content')
<table width="640" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
    <tr>
        <td align="center" valign="top">

            <table width="600" cellpadding="0" cellspacing="0" border="0" class="container">
                <tr>
                    <td width="600" class="mobile" align="center" valign="top">
                        <h1> {{$data['title']}} </h1>
                    </td>
                </tr>
                <tr>
                    <td width="600" class="mobile" align="center" valign="top">
                        <p class="ft-sz-16" style="padding-left: 20px; padding-right: 20px;">
                            {!!$data['description']!!}
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td height="10" style="font-size:10px; line-height:10px;">&nbsp;</td>
    </tr>
</table>
@endsection
