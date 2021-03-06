import React            from 'react';
import FilesListReducer from '../../redux/reducers/filesListReducer';

describe('Test filesListReducer reducer', () => {
  it('filesListReducer FILES_LIST_PROCESS_START must set loading to true', () => {
    const state = FilesListReducer({}, { type: 'FILES_LIST_PROCESS_START' });
    expect(state).toEqual({ loading: true });
  });

  it('filesListReducer FILES_LIST_PROCESS_FINISH set loading to false, errors to null and replace items', () => {
    const items = { items: [{}] };
    const state = FilesListReducer({}, { type: 'FILES_LIST_PROCESS_FINISH', items });
    expect(state).toEqual({ errors: null, loading: false, items });
  });

  it('filesListReducer FILES_LIST_ERROR must set loading to false and set errors array', () => {
    const errors = { errors: [{}] };
    const state = FilesListReducer({}, { type: 'FILES_LIST_ERROR', errors });
    expect(state).toEqual({ loading: false, errors });
  });

  it('filesListReducer FILES_LIST_DELETE set loading to false, errors to null and remove one item', () => {
    const itemsAfterDelete = [{ name: 'file2' }];
    const state = FilesListReducer({ items: [{ name: 'file1' }, { name: 'file2' }] }, {
      type: 'FILES_LIST_DELETE',
      item: { name: 'file1' }
    });
    expect(state).toEqual({ errors: null, loading: false, items: itemsAfterDelete });
  });

  it('filesListReducer FILES_LIST_ADD set loading to false and add one item', () => {
    const itemsAfterAdd = [{ name: 'file1' }, { name: 'file2' }, { name: 'file3' }];
    const state = FilesListReducer({ items: [{ name: 'file1' }, { name: 'file2' }] }, {
      type: 'FILES_LIST_ADD',
      item: { name: 'file3' }
    });
    expect(state).toEqual({ loading: false, items: itemsAfterAdd });
  });

  it('filesListReducer FILES_LIST_UPDATE set loading to false and replace one item', () => {
    const itemsAfterUpdate = [{ name: 'file1' }, { name: 'file3' }];
    const state = FilesListReducer({ items: [{ name: 'file1' }, { name: 'file2' }] }, {
      type: 'FILES_LIST_UPDATE',
      name: 'file2',
      item: { name: 'file3' }
    });
    expect(state).toEqual({ loading: false, items: itemsAfterUpdate });
  });
});
