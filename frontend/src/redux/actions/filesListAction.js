export const FILES_LIST_PROCESS_START = 'FILES_LIST_PROCESS_START';
export const FILES_LIST_PROCESS_FINISH = 'FILES_LIST_PROCESS_FINISH';
export const FILES_LIST_ERROR = 'FILES_LIST_ERROR';

export function filesListProcessStart() {
  return { type: FILES_LIST_PROCESS_START };
}

export function filesListProcessFinish(items) {
  return { type: FILES_LIST_PROCESS_FINISH, items };
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
        return response.json();
      })
      .then((response) => {
        dispatch(filesListProcessFinish(response.items));
      })
      .catch((error) => {
        dispatch(filesListError(JSON.stringify(error)));
      });
  };
}
