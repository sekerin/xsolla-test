import { modalError, modalHide, modalLoad } from './modalAction';

export const FILES_LIST_PROCESS_START = 'FILES_LIST_PROCESS_START';
export const FILES_LIST_PROCESS_FINISH = 'FILES_LIST_PROCESS_FINISH';
export const FILES_LIST_ADD = 'FILES_LIST_ADD';
export const FILES_LIST_UPDATE = 'FILES_LIST_UPDATE';
export const FILES_LIST_DELETE = 'FILES_LIST_DELETE';
export const FILES_LIST_ERROR = 'FILES_LIST_ERROR';

export function filesListProcessStart() {
  return { type: FILES_LIST_PROCESS_START };
}

export function filesListProcessFinish(items) {
  return { type: FILES_LIST_PROCESS_FINISH, items };
}

export function filesListAdd(item) {
  return { type: FILES_LIST_ADD, item };
}

export function filesListUpdate(name, item) {
  return { type: FILES_LIST_UPDATE, name, item };
}

export function filesListDelete(item) {
  return { type: FILES_LIST_DELETE, item };
}

export function filesListError(errors) {
  return { type: FILES_LIST_ERROR, errors };
}

export function listRequest() {
  return (dispatch) => {
    dispatch(filesListProcessStart());

    fetch(`//${BACKEND_URL}/`, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json; charset=utf-8'
      },
      credentials: 'include'
    })
      .then((response) => {
        response.json().then((item) => {
          if (response.status === 200) {
            dispatch(filesListProcessFinish(item.items));
          } else {
            dispatch(filesListError(item.errors));
          }
        }).catch((error) => dispatch(filesListError(error)));
      })
      .catch((error) => {
        dispatch(filesListError(JSON.stringify(error)));
      });
  };
}

export function remove(id) {
  return (dispatch) => {
    dispatch(filesListProcessStart());
    fetch(`//${BACKEND_URL}/${id}`, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      credentials: 'include',
      method: 'DELETE'
    })
      .then((response) => {
        response.json().then((item) => {
          if (response.status === 200) {
            dispatch(filesListDelete(item.item));
          } else {
            dispatch(filesListError(item.errors));
          }
        }).catch((error) => dispatch(filesListError(error)));
      })
      .catch((error) => {
        dispatch(filesListError(JSON.stringify(error)));
      });
  };
}

export function create(file) {
  return (dispatch) => {
    if (file === null) {
      return dispatch(modalError('input Error'));
    }

    dispatch(modalLoad());

    const formData = new FormData();

    formData.append('file', file);
    fetch(`//${BACKEND_URL}/`, {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'include',
      method: 'POST',
      body: formData
    })
      .then((response) => {
        response.json().then((item) => {
          if (response.status === 200) {
            dispatch(filesListAdd(item.item));
            dispatch(modalHide());
          } else {
            dispatch(modalError(item.errors));
          }
        }).catch((error) => dispatch(modalError(error)));
      })
      .catch((error) => {
        dispatch(modalError(error));
      });
  };
}

export function update(name, file) {
  return (dispatch) => {
    dispatch(modalLoad());

    const formData = new FormData();

    formData.append('file', file);
    fetch(`//${BACKEND_URL}/${name}`, {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'include',
      method: 'POST',
      body: formData
    })
      .then((response) => {
        response.json().then((item) => {
          if (response.status === 200) {
            dispatch(filesListUpdate(name, item.item));
            dispatch(modalHide());
          } else {
            dispatch(modalError(item.errors));
          }
        }).catch((error) => dispatch(modalError(error)));
      })
      .catch((error) => {
        dispatch(modalError(error));
      });
  };
}
