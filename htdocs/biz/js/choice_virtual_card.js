  $(document).ready(function(){
    var ids = $('#cardids', parent.document).val();
    var str = new String();
    var arr = new Array();

    idsArr = ids.split(',');

    for (var i = 0; i < idsArr.length; i++) {
      $('.listNum'+idsArr[i]).attr('checked', 'checked');
    };
  });

	$('#addInsert').click(function(){
    var idList = '';
		$('input[name="select"]:checked').each(function(){
			idList += $(this).val() + ',';
		});

    $('input[name=Count]', parent.document).val($('input[name="select"]:checked').size());

    $('#cardids', parent.document).val(idList);
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
	});

  $('#choiceAll').click(function(){ $('input[name="select"]').attr('checked', 'checked'); });
  $('#noChoice').click(function(){ $('input[name="select"]').removeAttr('checked'); });