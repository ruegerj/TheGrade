<div id="subjectEditModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Area</h5>        
      </div>
      <div class="modal-body">
        <form areaAdd method="post">
          <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken; ?>">
          <input type="hidden" name="areaId" value="<? echo $areaId; ?>">
          <input type="text" name="title" class="from-control p-1 border mb-3 w-100 rounded" placeholder="Subject title" autocomplete="off">
          <textarea name="description" maxlength="<? echo $GLOBALS["config"]["validate"]["description"]["max"]; ?>" 
          cols="30" rows="8" class="form-control p-1 border mb-3 rounded" placeholder="Subject description"></textarea>
          <input type="number" name="grading" class="from-control p-1 border mb-3 w-100 rounded" placeholder="Subject grading in %" min="1" max="500" 
          title="Subject grading in %">
        </form>
      </div>
      <div class="modal-footer">
        <button cancel type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
        <button edit type="button" class="btn btn-secondary">Add</button>
      </div>
    </div>
  </div>
</div>
<script>
  let subjectModal = (function () {
    const modalTitleElem = document.querySelector('.modal-title');
    const descriptionElem = document.querySelector('textarea[name="description"]');
    const titleElem = document.querySelector('input[name="title"]');
    const gradingElem = document.querySelector('input[type="number"][name="grading"]');
    const submitBtn = document.querySelector('button[edit]');
    const cancelBtn = document.querySelector('button[cancel]');
    const form = document.querySelector('form[areaAdd]');
    const titleConMax = <? echo $GLOBALS["config"]["validate"]["title"]["max"]; ?>;    
    const titleConMin = <? echo $GLOBALS["config"]["validate"]["title"]["min"]; ?>;    
    const descriptionConMax = <? echo $GLOBALS["config"]["validate"]["description"]["max"]; ?>;
    const descriptionConMin = <? echo $GLOBALS["config"]["validate"]["description"]["min"]; ?>;
    const gradingConMax = <? echo $GLOBALS["config"]["validate"]["grading"]["max"]; ?>;
    const gradingConMin = <? echo $GLOBALS["config"]["validate"]["grading"]["min"]; ?>;
    let enableAutocompleteDescription = true;

    document.addEventListener('DOMContentLoaded', () => {
            titleElem.addEventListener('input', () => {
            titleElem.classList.remove('border-danger');
            if (enableAutocompleteDescription === true) {
                let title = titleElem.value;
                if (title.length > 0) {
                    const defaultDescription = 'Description of subject ';
                    descriptionElem.textContent = defaultDescription + title;   
                    descriptionElem.classList.remove('border-danger');
                } else {
                    descriptionElem.textContent = '';
                }          
            }        
        });  

        descriptionElem.addEventListener('input', () => {
            descriptionElem.classList.remove('border-danger');
        });

        gradingElem.addEventListener('input', () => {
            gradingElem.classList.remove('border-danger');
        });

        submitBtn.addEventListener('click', () => {
        if (validate() === true) {          
            form.submit();
        }
        });   

        $('#subjectEditModal').on('hide.bs.modal', clear);
    });

    function validate()
    {
      const titleContent = titleElem.value.length;
      const descriptionContent = descriptionElem.textLength;
      const gradingContent = gradingElem.value;
      let titleValid, descriptionValid, gradingValid;
      if (titleContent > titleConMin && titleContent <= titleConMax) {
          titleValid = true;          
      } else {
        titleValid = false;
        titleElem.classList.add('border-danger');
      }
      if (descriptionContent > descriptionConMin && descriptionContent <= descriptionConMax) {
        descriptionValid = true;
      } else {
        descriptionValid = false;
        descriptionElem.classList.add('border-danger');
      }
      if (gradingContent > gradingConMin && gradingContent <= gradingConMax) {
        gradingValid = true;
      } else{
        gradingValid = false;
        gradingElem.classList.add('border-danger');
      }
      if (titleValid && descriptionValid && gradingValid) {
        return true;
      } else {
          return false;
      }
    }

    function clear()
    {
      form.reset();
      titleElem.classList.remove('border-danger');
      descriptionElem.classList.remove('border-danger');
      descriptionElem.textContent = '';
      gradingElem.classList.remove('border-danger');
    }

    function launchModal(newArea = true, data)
    {
      if (newArea === false) {
        const subjectIdElem = '<input type="hidden" name="subjectId" value="' + data.Id + '" />'
        form.insertAdjacentHTML('afterbegin', subjectIdElem);
        form.setAttribute('action', '/subject-edit');
        modalTitleElem.textContent = 'Edit subject';
        submitBtn.textContent = 'Save Changes';
        titleElem.value = data.Title;
        descriptionElem.textContent = data.Description;
        gradingElem.value = Math.floor(data.Grading * 100);
        enableAutocompleteDescription = false;
      } else {        
        form.setAttribute('action', '/subject-add');
        modalTitleElem.textContent = 'Add Subject';
        submitBtn.textContent = 'Add';
        gradingElem.value = "100";
        enableAutocompleteDescription = true;
      }
      $('#subjectEditModal').modal();
    }

    return {
      showModal: launchModal   
    }
  })();
</script>