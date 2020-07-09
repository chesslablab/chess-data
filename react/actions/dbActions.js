import dbActionTypes from 'constants/dbActionTypes';

export const accept = (data) =>
{
  return {
      type: dbActionTypes.CLICK_ACCEPT,
      payload: data
    }
}

export const cancel = () =>
{
  return {
      type: dbActionTypes.CLICK_CANCEL
    }
}

export const connect = () =>
{
  return {
      type: dbActionTypes.CLICK_CONNECT
    }
}

export const disconnect = () =>
{
  return {
      type: dbActionTypes.CLICK_DISCONNECT
    }
}
