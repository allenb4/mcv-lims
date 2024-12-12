<html>
    <head>
        <style>
            img{
                margin-bottom: 10px;
            }
          
            @page {
                margin-left: 5px;
                margin-right: 5px;
                margin-top: 5px;
                margin-bottom: 5px;
            }
        </style>
        <title>
            {{__('Barcode')}} - #{{$group['id']}}
        </title>
    </head>
    <body>
        @for ($i = 0; $i < $number; $i++)
        @if($i>0)
        <pagebreak>
        @endif
        <table width="100%" style="width:100%;text-algin:center">
            <tr>
                <td align="center">
                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($group['barcode'], $barcode_settings['type'])}}" alt="barcode" class="margin" width="100%"/>
                    <b>
                        {{$group['barcode']}}
                    </b>
                </td>
            </tr>
            <tr>
                <td align="center">
                    {{$group['patient']['name']}} 
                </td>
            </tr>
            <tr>
                <td align="center">
                    {{$group['patient']['age']}}  / {{$group['patient']['gender']}}
                </td>
            </tr>
        </table>
        @if($i>0)
        </pagebreak>
        @endif
        @endfor
    </body>
</html>
