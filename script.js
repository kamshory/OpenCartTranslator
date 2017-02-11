$(document).ready(function(e) {
    $(document).on('click', '.filelist ul li a', function(e){
		var group = $(this).attr('data-group');
		var module = $(this).attr('data-module');
		var missing = "";
		$('head meta[name="group"]').attr('content', group);
		$('head meta[name="module"]').attr('content', module);
		
		var dir = $('head meta[name="dir"]').attr('content');
		var lang2edit = $('head meta[name="lang2edit"]').attr('content');
		var langref = $('head meta[name="langref"]').attr('content');
		
		$('#val2edit').val('').attr({'data-save':'false'});
		$('#valref').val('').attr({'data-save':'false'});
		
		$('.filelist a').removeClass('selected');
		$(this).addClass('selected');
		
		$.ajax({
			url:'load-key.php',
			data:{dir:dir, group:group, module:module, langref:langref, lang2edit:lang2edit},
			dataType:"json",
			type:'GET',
			success: function(data){
				$('.keylist ul').empty();
				var idx = 0;
				for(var i in data)
				{
					if(data[i].exists)
					{
						missing = '';
						missingidx = '';
					}
					else
					{
						missing = " *";
						missingidx = ' data-missing-index="'+idx+'"';
						idx++;
					}
					$('.keylist ul').append('<li><a href="#" data-key="'+data[i].key+'"'+missingidx+'>'+data[i].key+missing+'</a></li>');
				}
				if(idx>0)
				{
					$('.keylist ul li a[data-missing-index="0"]').click();
				}
			}
		});
		return false;
	});
    $(document).on('click', '.keylist ul li a', function(e){
		var key = $(this).attr('data-key');
		$('head meta[name="key"]').attr('content', key);
		var group = $('head meta[name="group"]').attr('content');
		var module = $('head meta[name="module"]').attr('content');
		var dir = $('head meta[name="dir"]').attr('content');
		var lang2edit = $('head meta[name="lang2edit"]').attr('content');
		var langref = $('head meta[name="langref"]').attr('content');
		$('.keylist a').removeClass('selected');
		$(this).addClass('selected');
		$('.key2edit').text(key);
		$.ajax({
			url:'load-value.php',
			data:{dir:dir, group:group, module:module, langref:langref, lang2edit:lang2edit, key:key},
			dataType:"json",
			type:'GET',
			success: function(data){
				$('#val2edit').val(data.edit).attr({
					'data-dir':dir,
					'data-lang2edit':lang2edit,
					'data-langref':langref,
					'data-group':group,
					'data-key':key,
					'data-module':module,
					'data-save':'true'
				});
				$('#valref').val(data.ref);
			}
		});
		return false;
	});
});
function saveVal(selector)
{
	var dir = $(selector).attr('data-dir');
	var lang2edit = $(selector).attr('data-lang2edit');
	var group = $(selector).attr('data-group');
	var module = $(selector).attr('data-module');
	var key = $(selector).attr('data-key');
	var value = $(selector).val();
	var oke = $(selector).attr('data-save');
	if(oke == 'true')
	{
		$('.status').html('Saving...');
		$.ajax({
			url:'set-value.php',
			data:{dir:dir, group:group, module:module, lang2edit:lang2edit, key:key, value:value},
			dataType:"html",
			type:'POST',
			success: function(data){
				$('.status').html('Saved');
				setTimeout(function(){$('.status').html('&nbsp;');}, 1000);
			}
		});
	}
	else
	{
		alert('Can not save.');
	}
}
function clearVal(selector)
{
	var dir = $(selector).attr('data-dir');
	var lang2edit = $(selector).attr('data-lang2edit');
	var langref = $(selector).attr('data-langref');
	var group = $(selector).attr('data-group');
	var module = $(selector).attr('data-module');
	var key = $(selector).attr('data-key');
	var oke = $(selector).attr('data-save');
	if(oke == 'true')
	{
		if(confirm('Clear language mean remove all not necessary data from language package. Are you sure to clear this language?'))
		{
			$('.status').html('Clearing...');
			$.ajax({
				url:'clear-value.php',
				data:{dir:dir, group:group, module:module, langref:langref, lang2edit:lang2edit},
				dataType:"html",
				type:'POST',
				success: function(data){
					$('.status').html('Clear');
					setTimeout(function(){$('.status').html('&nbsp;');}, 1000);
				}
			});
		}
	}
	else
	{
		alert('Can not clear.');
	}
}
function prevVal()
{
	var curidx = $('.keylist ul li a.selected').attr('data-missing-index') || "0";
	curidx = parseInt(curidx); 
	curidx--;
	var idx = $('.keylist ul li a[data-missing-index="'+curidx+'"]').length;
	if(idx>0)
	{
		$('.keylist ul li a[data-missing-index="'+curidx+'"]').click();
	}
}
function nextVal()
{
	var curidx = $('.keylist ul li a.selected').attr('data-missing-index') || "0";
	curidx = parseInt(curidx); 
	curidx++;
	var idx = $('.keylist ul li a[data-missing-index="'+curidx+'"]').length;
	if(idx>0)
	{
		$('.keylist ul li a[data-missing-index="'+curidx+'"]').click();
	}
}