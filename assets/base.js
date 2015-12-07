(function(target) {
  'use strict'
  function ajax(Url, Method, Contents) {
    return new Promise(function(resolve, reject) {
      const XHR = new XMLHttpRequest()
      XHR.open(Method, Url, true)
      XHR.addEventListener('load', () => {
        resolve(XHR.responseText)
      })
      XHR.addEventListener('error', reject)
      XHR.setRequestHeader('X-Auth', 'COOKIE');
      XHR.send(typeof Contents === 'object' ? JSON.stringify(Contents) : Contents)
    })
  }

  function handleAjaxResponse(Callback, form, elements) {
    const Callable = function(RawResponse) {
      let Response = null
      try {
        Response = JSON.parse(RawResponse)
      } catch (_) {
        throw new Error('Unable to parse response from server')
      }
      if (Response.status) {
        Callback(Response)
      } else if (Response.message) {
        alert(Response.message)
      } else if (Response.fields) {
        Response.fields.forEach(function(field) {
          // TODO: Show this message above fields
          alert(field.name + ': ' + field.message);
        })
      } else {
        console.log('Unknown response type', Response)
      }
    }
    return Callable
  }

  function lock(Callback) {
    let InProgress = false

    const Callable = function(Param) {
      if (!InProgress) {
        const ReturnValue = Callback.call(this, Param)
        InProgress = true

        if (ReturnValue && ReturnValue.constructor.name === 'Promise') {
          ReturnValue.then(function() {
            InProgress = false
          }, function(e) {
            console.error(e)
            InProgress = false
          })

        } else InProgress = false
      }
    }

    return Callable
  }

  function getElements(form) {
    const elements = {}
    Array.prototype.forEach.call(form.querySelectorAll('[name]'), function(element) {
      elements[element.name] = element
    })
    return elements
  }

  function getValues(elements) {
    const values = {}
    for (const key in elements) {
      const element = elements[key]

      values[key] = element.value
    }
    return values
  }

  window.App = {lock, ajax, getElements, getValues, handleAjaxResponse}
})(window)
