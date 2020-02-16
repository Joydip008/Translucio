<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{route('chat_box')}}" method="post">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Origin Language &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="origin_language" required >
                <option value=""> -- Select One --</option>
                @foreach ($LanguagesList as $Language)
                    <option value="{{ $Language->sortname }}" >{{ $Language->name }}</option>
                @endforeach          
            </select>
        </div>  

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Destination Language &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="destination_language" required >
                <option value=""> -- Select One --</option>
                @foreach ($LanguagesList as $Language)
                    <option value="{{ $Language->sortname }}" >{{ $Language->name }}</option>
                @endforeach          
            </select>
        </div> 

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Api Select &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="api" required >
                <option value=""> -- Select One --</option>
                <option value="A" >Amazon</option>
                <option value="D" >Deepl</option>
                <option value="G" >Google</option>
            </select>
        </div> 

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">DB CHECK &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="db" >
                <option value=""> -- Select One --</option>
                <option value="Y" >DB Check</option>
            </select>
        </div> 

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Wite Text &nbsp;<i class="fa fa-star star"></i></label>
            </div>
            <textarea rows="4" cols="50" name="text">
            </textarea>
        </div> 
        <button type="submit" class="btn btn_project_submit">Submit</button>
    </form>      
    @if(!empty($TranslatedText))
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Translated Text &nbsp;<i class="fa fa-star star"></i></label>
            </div>
            <textarea rows="4" cols="50">{{$TranslatedText}}
            </textarea>
        </div>  
    @endif  
</body>
</html>