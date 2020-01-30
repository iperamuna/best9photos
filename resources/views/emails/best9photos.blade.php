<html>
    <body>
        <h1>Best 9 Facebook Photos From Your Last Year</h1>
        <p>
            Dear Mr. {{ $user->name }},
        </p>
        <table>
            @foreach($photos as $photo)
            <tr>
                <td width="800">
                    <img src="{{ $message->embed($photo['image_src']) }}">
                </td>
            </tr>
            @endforeach
        </table>
        <br>
        <hr>
        From Best 9 Photos Team.
    </body>
</html>
