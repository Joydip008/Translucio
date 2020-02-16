<!DOCTYPE html>
<html>

<center>
<div id='loadingmessage' style='display:none'>
  <img src="{{asset ('assets/upload/loading.gif')}}">
</div>
<center>

<body>

    <select id="ChooseLanguage">
        <option value=""> -- Select One --</option>

        @foreach($LanguagePairList as $LanguagePair)
        <option value="{{$LanguagePair['sortname']}}">{{$LanguagePair['name']}}</option>
        @endforeach

    </select>

</body> 
</html>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#ChooseLanguage").change(function () {
            var selectedText = $(this).find("option:selected").text();
            var selectedValue = $(this).val();
 
            //alert(selectedValue);
           // window.location.href = "{{url('/api/test-api1?lang_code="selectedValue"')}}";

                
                $(document).ajaxStart(function(){
                // Show image container
                // $("#main").hide();
                $("#loadingmessage").show();
                //alert("OKAY");
                });
                $(document).ajaxComplete(function(){
                // Hide image container
                $("#loadingmessage").hide();
                // $("#main").show();
                });


                $.ajax({ 
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                url:"{{url('/api/test-api1?lang_code=DE')}}",
                type: "POST",
                data: {'text': '<html><head><title>Page Title</title></head><body><h1>This is a Heading</h1><p>This is a paragraph.</p></body></html>'},
                dataType : 'json',
                success: function(response){
            
                        console.log(response);
                    
                    }
                });
                
                });

        });
</script>
