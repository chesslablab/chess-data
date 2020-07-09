import dbActionTypes from 'constants/dbActionTypes';

const db = (state = {}, action) => {
  switch (action.type) {
    case dbActionTypes.CLICK_ACCEPT:
      // TODO: connect to database
      return {
        ...state,
        connected: true,
        modal: {
          open: false
        }
      };
    case dbActionTypes.CLICK_CANCEL:
      return {
        ...state,
        modal: {
          open: false
        }
      };
    case dbActionTypes.CLICK_CONNECT:
      return {
        ...state,
        connected: false,
        modal: {
          open: true
        }
      };
    case dbActionTypes.CLICK_DISCONNECT:
      // TODO: disconnect from database
      return {
        ...state,
        connected: false,
        modal: {
          open: false
        }
      };
    default:
      return {
        ...state,
        modal: {
          open: false
        }
      };
  }
};

export default db;
