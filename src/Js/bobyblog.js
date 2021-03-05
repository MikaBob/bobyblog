$('a#happenedDate').click(() => {
    getPosts('happenedDate', $('#currentDirection').val(), $('#currentPage').val());
});

$('a#creationDate').click(() => {
    getPosts('creationDate', $('#currentDirection').val(), $('#currentPage').val());
});

function getPosts(orderBy, direction, offset) {
    var postContainer = $('.postContainer');
    console.log(orderBy, direction, offset);
    $.ajax({
            method: 'GET',
            url: '/post/get',
            data: {orderBy: orderBy, direction: direction, offset: offset},
            beforeSend: () => {
                //startLoading();
                if(orderBy !== $('#currentOrderBy').val() || offset === 0){
                    $('#currentOrderBy').val(orderBy);
                    postContainer.html('');
                } else if (orderBy === $('#currentOrderBy').val()){
                    postContainer.html('');
                }
                $('#currentDirection').val($('#currentDirection').val()=== 'ASC' ? 'DESC':'ASC');
            }
    })
    .done((response) => {
        postContainer.append(response);

    })
    .always(() => {
            //stopLoading();
    });
}

$('a#happenedDate').click();