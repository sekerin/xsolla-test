export const MODAL_SHOW = 'MODAL_SHOW';
export const MODAL_HIDE = 'MODAL_HIDE';
export const MODAL_LOAD = 'MODAL_LOAD';
export const MODAL_CHANGE_FILE = 'MODAL_CHANGE_FILE';
export const MODAL_ERROR = 'MODAL_ERROR';
export const MODAL_UPDATE = 'MODAL_UPDATE';


export function modalShow() {
  return { type: MODAL_SHOW };
}

export function modalShowUpdate(item) {
  return { type: MODAL_UPDATE, item };
}

export function modalHide() {
  return { type: MODAL_HIDE };
}

export function modalLoad() {
  return { type: MODAL_LOAD };
}

export function modalError(error) {
  return { type: MODAL_ERROR, error };
}

export function modalChangeFile(value) {
  return { type: MODAL_CHANGE_FILE, value };
}
