<div id="areaAddModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Area</h5>        
      </div>
      <div class="modal-body">
        <form areaAdd method="post">
          <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken; ?>">
          <input type="text" name="title" class="from-control p-1 border mb-3 w-100 rounded" placeholder="Area title" autocomplete="off">
          <textarea name="description" maxlength="<? echo $GLOBALS["config"]["validate"]["description"]["max"]; ?>" 
          cols="30" rows="10" class="form-control p-1 border mb-3 rounded" placeholder="Area description"></textarea>
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
  let areaModal = (function () {
    const modalTitleElem = document.querySelector('.modal-title');
    const descriptionElem = document.querySelector('textarea[name="description"]');
    const titleElem = document.querySelector('input[name="title"]');
    const submitBtn = document.querySelector('button[edit]');
    const cancelBtn = document.querySelector('button[cancel]');
    const form = document.querySelector('form[areaAdd]');
    const titleConMax = <? echo $GLOBALS["config"]["validate"]["title"]["max"]; ?>;    
    const titleConMin = <? echo $GLOBALS["config"]["validate"]["title"]["min"]; ?>;    
    const descriptionConMax = <? echo $GLOBALS["config"]["validate"]["description"]["max"]; ?>;
    const descriptionConMin = <? echo $GLOBALS["config"]["validate"]["description"]["min"]; ?>;
    const redBorder = 'border-danger';
    let enableAutocompleteDescription = true;

    document.addEventListener('DOMContentLoaded', () => {
      titleElem.addEventListener('input', () => {
        titleElem.classList.remove(redBorder);
        if (enableAutocompleteDescription === true) {
          let title = titleElem.value;
          if (title.length > 0) {
            const defaultDescription = 'Description of area ';
            descriptionElem.textContent = defaultDescription + title;  
            descriptionElem.classList.remove(redBorder);      
          } else {
            descriptionElem.textContent = '';
          }          
        }
      });      

      descriptionElem.addEventListener('input', () => {
        descriptionElem.classList.remove(redBorder);
      });

      submitBtn.addEventListener('click', () => {
        if (validate() === true) {          
          form.submit();
        }
      });         

      $('#areaAddModal').on('hide.bs.modal', clear);
    });

    function validate()
    {
      const titleContent = titleElem.value.length;
      const descriptionContent = descriptionElem.textLength;
      let titleValid, descriptionValid, gradingValid;
      if (titleContent > titleConMin && titleContent <= titleConMax) {
          titleValid = true;          
      } else {
        titleValid = false;
        titleElem.classList.add(redBorder);
      }
      if (descriptionContent > descriptionConMin && descriptionContent <= descriptionConMax) {
        descriptionValid = true;
      } else {
        descriptionValid = false;
        descriptionElem.classList.add(redBorder);
      }
      if (titleValid && descriptionValid) {
        return true;
      } else {
        return false;
      }
    }

    function clear()
    {
      form.reset();
      titleElem.classList.remove(redBorder);
      descriptionElem.classList.remove(redBorder);
      descriptionElem.textContent = '';
    }

    function launchModal(newArea = true, data)
    {
      if (newArea === false) {
        const areaIdElem = '<input type="hidden" name="areaId" value="' + data.Id + '" />'
        form.insertAdjacentHTML('afterbegin', areaIdElem);
        form.setAttribute('action', '/area-edit');
        modalTitleElem.textContent = 'Edit Area';
        submitBtn.textContent = 'Save Changes';
        titleElem.value = data.Title;
        descriptionElem.textContent = data.Description;
        enableAutocompleteDescription = false;
      } else {        
        form.setAttribute('action', '/area-add');
        modalTitleElem.textContent = 'Add Area';
        submitBtn.textContent = 'Add';
        enableAutocompleteDescription = true;
      }
      $('#areaAddModal').modal();
    }

    return {
      showModal: launchModal   
    }
  })();
</script>