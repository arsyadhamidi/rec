<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            font-size: 12pt;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif'

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg" style="text-align: center">
                <img src="{{ public_path('images/logo-1.png') }}" width="400">
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-lg" style="text-align: center">
                <p><u><b>Data Izin Pulang Cepat</b></u></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg">
                <table width="100%">
                    <tr>
                        <td width="10%">Perihal</td>
                        <td style="width: 3%">:</td>
                        <td width="57%">
                            Download Data Izin Pulang Cepat
                        </td>
                        <td>
                            Padang, {{ strftime('%d %B %Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td colspan="2">-</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg">
                <table style="width: 100%;border-collapse: collapse; border:1px solid black; margin-top: 30px;">
                    <tr style="border:
                    1px solid black;">
                        <th width="5%" style="border-right: 1px solid black; padding: 10px 0px">No.</th>
                        <th style="border-right: 1px solid black">Nama</th>
                        <th style="border-right: 1px solid black">Tgl.Izin</th>
                        <th style="border-right: 1px solid black">Jam.Pulang</th>
                        <th style="border-right: 1px solid black">Jam.Selesai</th>
                        <th style="border-right: 1px solid black">Alasan</th>
                        <th style="border-right: 1px solid black">Atasan</th>
                    </tr>
                    @foreach ($pulangs as $data)
                        <tr style="border: 1px solid black">
                            <td style="border-right: 1px solid black; text-align:center; padding: 10px 0px">
                                {{ $loop->iteration }}</td>
                            <td style="border-right: 1px solid black; padding: 0xp 7px;">{{ $data->nama_karyawan ?? '-' }}</td>
                            <td style="border-right: 1px solid black; padding: 0xp 7px;">{{ $data->tgl_izin ?? '-' }}</td>
                            <td style="border-right: 1px solid black; padding: 0xp 7px;">{{ $data->jam_pulang ?? '-' }}</td>
                            <td style="border-right: 1px solid black; padding: 0xp 7px;">{{ $data->jam_selesai ?? '-' }}</td>
                            <td style="border-right: 1px solid black; padding: 0xp 7px;">{{ $data->alasan ?? '-' }}</td>
                            <td style="border-right: 1px solid black; padding: 0xp 7px;">{{ $data->nama_atasan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</body>

</html>
