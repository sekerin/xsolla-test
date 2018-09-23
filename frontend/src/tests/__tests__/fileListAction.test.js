import {
  filesListProcessStart,
  filesListProcessFinish,
  filesListError
} from '../../redux/actions/filesListAction';

describe('Test filesListAction redux action', () => {
  it('filesListProcessStart', () => {
    const start = filesListProcessStart();
    expect(start).toEqual({ type: 'FILES_LIST_PROCESS_START' });
  });

  it('filesListProcessFinish', () => {
    const items = [{}];
    const finish = filesListProcessFinish(items);
    expect(finish).toEqual({ type: 'FILES_LIST_PROCESS_FINISH', items });
  });

  it('filesListError', () => {
    const errors = ['error'];
    const finish = filesListError(errors);
    expect(finish).toEqual({ type: 'FILES_LIST_ERROR', errors });
  });
});
